---
title: Customising the views
weight: 5
---

You can customize any of the views by publishing them. The view can be published by issuing this artisan command.

```bash
php artisan vendor:publish --tag="comments-views"
```

You'll find the published views in the `resources/views/vendor/comments` directory of your app.

# Customizing the CSS

The default CSS of the package is rendered by the `<x-comments::styles/>` component that you put in the `<head>` of your document. 
<br>See the [installation instructions](https://spatie.be/docs/laravel-comments/v1/livewire-components/installation#content-using-the-assets).

You can of course include these styles in your own build process and tweak the contents of the CSS source file, available [on GitHub](https://github.com/spatie/laravel-comments-livewire/blob/main/resources/css/comments.css#L49) (_private repo, avaliable for licensees_).

The package uses CSS vars for the colors. A fair middleground is overriding only these variables to set a different theme.

![screenshot](/docs/laravel-comments/v1/images/dark-mode.png)

Eg. you could implement a dark theme by using the default `<x-comments::styles/>` and change the colors in your application CSS:

```css
/* Dark colors */
.comments {    
    --comments-color-background: rgb(34, 34, 34);
    --comments-color-background: rgb(34, 34, 34);
    --comments-color-background-nested: rgb(34, 34, 34);
    --comments-color-background-paper: rgb(55, 51, 51);
    --comments-color-background-info: rgb(104, 89, 214);

    --comments-color-reaction: rgb(59, 59, 59);
    --comments-color-reaction-hover: rgb(65, 63, 63);
    --comments-color-reacted: rgba(67, 56, 202, 0.25);
    --comments-color-reacted-hover: rgba(67, 56, 202, 0.5);

    --comments-color-border: rgb(221, 221, 221);

    --comments-color-text:white;
    --comments-color-text-dimmed: rgb(164, 164, 164);
    --comments-color-text-inverse: white;

    --comments-color-accent: rgba(67, 56, 202);
    --comments-color-accent-hover: rgba(67, 56, 202, 0.75);

    --comments-color-danger: rgb(225, 29, 72);
    --comments-color-danger-hover: rgb(225, 29, 72, 0.75);

    --comments-color-success: rgb(10, 200, 134);
    --comments-color-success-hover: rgb(10, 200, 134, 0.75);

    --comments-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}
```

You might want to [set your Shiki theme](https://spatie.be/docs/laravel-comments/v1/installation-setup#content-customising-the-code-highlighting-theme) as well to fit the dark design.
