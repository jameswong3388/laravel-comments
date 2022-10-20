---
title: Installation
weight: 2
---

To use the Livewire components provided by this package, you must first install [Livewire](https://laravel-livewire.com) itself. Make sure you follow [their installation instructions](https://laravel-livewire.com/docs/2.x/installation).

After that, you should follow [the installation instructions](https://spatie.be/docs/laravel-comments/v1/laravel-comments) of the base spatie/laravel-comments package. After following these instructions, you should have migrated your database and prepared your model.

With that all done, you're ready to pull in the `spatie/laravel-comments-livewire` package

```bash
composer require spatie/laravel-comments-livewire
```

## Using the assets

On each page where you want to use the components, you should include the styles and scripts provided by the package. 

You can include the styles in the head of your page.


```html
<head>
    <!-- all other head stuff  -->
    <x-comments::styles />
</head>
```

The `script` should be included near the end of the body of the page.

```html
<body>
    <!-- your content -->
    <x-comments::scripts />
</body>
```

## Troubleshooting

If you want problems installing the package, take a look at [our demo app](https://github.com/spatie/laravel-comments-app) and compare it against yours.
