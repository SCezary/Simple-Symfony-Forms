<?php

namespace App\Form\Type\Post;

use App\Entity\Post;
use App\Entity\PostValue;
use App\Entity\Template;
use App\Entity\TemplateField;
use App\Repository\TemplateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function __construct(
        private readonly TemplateRepository $templateRepository,
    ) {
    }

    private function setTemplateFields(Template $template, Post &$post): void
    {
        $templateFields = $template->getTemplateFields()->toArray();
        array_walk($templateFields, function (TemplateField $templateField) use ($post, $template) {
            $postValue = new PostValue();
            $postValue->setPost($post);
            $postValue->setTemplate($template);
            $postValue->setTemplateField($templateField);

            $post->addPostValue($postValue);
        });
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'query_builder' => function (TemplateRepository $templateRepository) {
                    return $templateRepository->createQueryBuilder('template')
                        ->where('template.active = 1')
                        ->orderBy('template.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_value' => 'systemName',
                'required' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Post $post */
            $post = $event->getData();
            $template = $post->getTemplate();

            if ($template instanceof Template && ($post->getPostValues()->count() == 0) && $post->getId() === null) {
                $post->clearPostValues();
                $this->setTemplateFields($template, $post);

                $event->setData($post);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var Post $post */
            $post = $form->getData();
            $eventData = $event->getData();

            // Check template has changed and update template fields.
            if ($eventData['template'] != $post->getTemplate()->getSystemName()) {
                $template = $this->templateRepository->findBySystemName($eventData['template']);
                if ($template instanceof Template) {
                    $post->clearPostValues();
                    $this->setTemplateFields($template, $post);
                    $form->setData($post);
                }
            }
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $form->add('postValues', CollectionType::class, [
                'entry_type' => PostValueType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_delete' => true,
                'by_reference' => false,
            ]);

            $form->add('submit', SubmitType::class);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'init_template' => null
        ]);
    }
}
