# Form
Automated forms for models built with GranadaORM/Builder

# Installation

Simply install using composer:

```composer require granadaorm/form```

# Usage

First, load up a record from the database using the model built from the Builder:

```$affiliate = \MyCustomApp\Affiliate::model()->find_one();```

Then create a form, specifying the framework of the page, e.g. Bootstrap (bs3/bs4) or Bulma:

```$form = $affiliate->getForm(\Granada\Form\Bulma::class);```

And render it to the page:

```
echo $form->beginForm();
echo $form->renderFields();
echo $form->build()
    ->setType('submit')
    ->setValue('Save')
    ->render();
echo $form->endForm();
```

## Rendering Partial Forms

To only show select fields or set the order manually, you can specify the fields individually:

```
echo $form->beginForm();
echo $form->renderField('name');
echo $form->renderField('phone');
echo $form->build()
    ->setType('submit')
    ->setValue('Save')
    ->render();
echo $form->endForm();
```
