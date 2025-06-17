<?php

namespace App\Form\Type\Post;

use App\Entity\Enums\TemplateFieldTypeEnum;
use App\Entity\TemplateField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Collections\Collection;

class PostSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Collection $data */
        $data = $builder->getData();

        /** @var Request $request */
        $request = $options['request'] ?? null;

        if ($data instanceof Collection) {
            $queryParams = $request->query->all()['post_search'] ?? [];

            /** @var TemplateField $value */
            foreach ($data as $value) {
                if (!$value instanceof TemplateField) {
                    return;
                }

                if (in_array($value->getType()->value, [
                    TemplateFieldTypeEnum::DATETIME->value,
                    TemplateFieldTypeEnum::DATE->value,
                ])) {
                    $type = match ($value->getType()->value) {
                        TemplateFieldTypeEnum::DATE->value => DateType::class,
                        TemplateFieldTypeEnum::DATETIME->value => DateTimeType::class,
                    };

                    $minChild = "min_" . $value->getSystemName();
                    $maxChild = "max_" . $value->getSystemName();

                    $dataMin = null;
                    $dataMax = null;

                    try {
                        if (!empty($queryParams[$minChild])) {
                            $dataMin = new \DateTime($queryParams[$minChild]);
                        }

                        if (!empty($queryParams[$maxChild])) {
                            $dataMax = new \DateTime($queryParams[$maxChild]);
                        }
                    } catch (\Exception $e) {
                        // Prevent some errors. Nothing to do here.
                    }

                    $builder->add($minChild, $type, [
                        'data' => $dataMin,
                        'label' => "From " . $value->getLabel(),
                        'required' => false,
                        'widget' => 'single_text',
                    ]);

                    $builder->add($maxChild, $type, [
                        'data' => $dataMax,
                        'label' => "To " . $value->getLabel(),
                        'required' => false,
                        'widget' => 'single_text',
                    ]);
                } else {
                    $builder->add($value->getSystemName(), TextType::class, [
                        'data' => $queryParams[$value->getSystemName()] ?? null,
                        'label' => $value->getLabel(),
                        'required' => false,
                    ]);
                }
            }

            $builder->add('Search', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'request' => null,
            'init_template' => null,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}