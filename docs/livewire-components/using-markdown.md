---
title: Using Markdown
weight: 7
---

Comments can be entered using Markdown, a simple set of rules to apply styling. Think of it as vastly simplified HTML so that it's very easy to type for both developers and non-developers.

On this page, you can learn the most commonly used Markdown markup.

## Links

To create a link, put the name of the link between `[]` and the URL between `()`. Here is an example:

```txt
[The Beatles](https://thebeatles.com) are a well known band.
```

will be rendered as


[The Beatles](https://thebeatles.com) are a well known band.

## Images

To link to an image, you can do the same as creating a link. Put the title between `[]` and the URL between `()`. Additionally, add a `!` before the opening `[`.

```txt
Here is an image of the Beatles:

![The Beatles](/docs/laravel-comments/v1/images/beatles.jpg)
```

The above will be rendered as:

Here is an image of the Beatles:

![The Beatles](/docs/laravel-comments/v1/images/beatles.jpg)

## Bold text

You can make text bold by wrapping it between `**`.

```txt
The Beatles are a **well known** band.
```

The above will be rendered as:

The Beatles are a **well known** band.

## Italics

You can make text italics by wrapping it between `_`.

```txt
The Beatles are a _well known_ band.
```

The above will be rendered as:

The Beatles are a _well known_ band.

## Titles

You can create a title by letting a sentence start with `#`. The more `#` you add, the smaller the title will render

```txt
# The Beatles are a _well known_ band
## The Beatles are a _well known_ band
### The Beatles are a _well known_ band
```

The above will be rendered as:

# The Beatles are a well known band
## The Beatles are a well known band
### The Beatles are a well known band

## Creating a list

To create list, each item should be on its own line and start with `-`

```yaml
The Beatles consist of

- John
- Paul
- George
- Ringo
```

This above will be rendered as:

The Beatles consist of

- John
- Paul
- George
- Ringo

To create a numbered list, start each line with a `1.`. You can increment the number, but this isn't necessary.

````txt
The Beatles consist of

1. John
1. Paul
1. George
1. Ringo
````

This above will be rendered as:

The Beatles consist of

1. John
1. Paul
1. George
1. Ringo


## Code blocks

Code blocks can be highlighted by wrapping the code between \`\`\`\. 

Optionally, you can add the name of language after the opening backticks to hint in which language the code should be highlighted. 

You can use any of [these languages](https://github.com/shikijs/shiki/blob/main/docs/languages.md#all-languages) that are support by Shiki (which is used under the hood to highlight code).

````
```php
$revolution = 9;
```
````

This will be rendered as 

```php
$revolution = 9;
```


## Inline code

To style text as code in the middle of a sentence, wrap the code between backticks

```txt
The `getBack()` function can be used to redirect back.
```

The above will be rendered as: 

The `getBack()` function can be used to redirect back.

## Learning more

The examples are this page are the most commonly used Markdown markup. To learn more formatting possibilities, head over [the basic syntax guide of the Markdown guide](https://www.markdownguide.org/basic-syntax/).
