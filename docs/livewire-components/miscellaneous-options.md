---
title: Miscellaneous options
weight: 6
---

The most powerful way to customize the components, is by [publishing the views](/docs/laravel-comments/v1/livewire-components/customising-the-views) and editing them.

On this page, we'll list various options and ideas.

## Using another avatar provider

By default, when your commenting user doesn't have an avatar, we'll use [Gravatar](https://en.gravatar.com) as a fallback.

To use another avatar provider, [publish the views](/docs/laravel-comments/v1/livewire-components/customising-the-views), and modify the `avatar.blade.php` view.

## Hiding all avatars

If you don't want to show any avatars, you can add the `config.ui.show_avatars` option to the `comments` config file

```php
// config/comments

return [
    // other options...
    
    'ui' => [
        'show_avatars' => false,
    ],
];
```

You can also use the `hide-avatars` attribute on the `comments` component.

```html
<livewire:comments :model="$post" hide-avatars />
```

## Choosing an editor

By default, we'll use [SimpleMDE](https://simplemde.com) to create and edit comments. Should you want to use a plan textarea, add this to the `comments.php` config file.

```php
// config/comments

return [
    // other options...
    
    'ui' => [
        'editor' => 'comments::editors.textarea',
    ],
];
```

## Making the comments read only

To disable creating, editing, and deleting comments on component, use `read-only` attribute.

```html
<livewire:comments :model="$post" read-only />
```

This can be handy to have fine-grained control over which user should be able to post comments for a certain model. Let's assume that you've implemented a method `canPostComment` on your used model that will return `true` if the user is allowed to comment on a given `$post`

```blade
@if(auth()->user()->canPostComment($post))
    <livewire:comments :model="$post" />
@else
    <livewire:comments :model="$post" read-only />
    
    You are not allow to post new comments on this post.
@endif
```

## Displaying the newest comments first

By default, the components show the oldest comments first. If you want to show the newest comments first, pass the `newest-first` attribute.

```html
<livewire:comments :model="$post" newest-first />
```

## Customizing the "No comments yet text"

You can customize that text that displayed when there are no comments yet but publishing the translations and editing the `no_comments_yet` yet key in the `comments` language file.

You can also customize this text per component by passing in a string to the `no-comments-text` prop.

```html
<livewire:comments :model="$post" no-comments-text="What are your thoughts on this?" />
```

## Only allow top level comments

By default, the Livewire components will allow two levels of comments. A comment on a comment is called a reply. If you want to disable replies, pass the `no-replies` attribute.

```html
<livewire:comments :model="$post" no-replies />
```
