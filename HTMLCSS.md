# HTML/CSS

# Style Guide / Code conventions / Linters

stylelint вроде бы умеет чекать SMACSS/BEM/... (изучить нужно)

## Related Materials

BEM 101:\
https://css-tricks.com/bem-101/

SASS/CSS common style conventions:\
https://github.com/airbnb/css

HTML5 Code Conventions:\
https://www.w3schools.com/HTML/html5_syntax.asp

Mix Sass BEM and Bootstrap:\
https://medium.com/@andersonorui_/bem-sass-and-bootstrap-9f89dc07d20f

Не оригинальный BEM, версия Nicolas Gallagher, которой все предпочитают пользоваться, потому, что она намного прозрачне:\
https://csswizardry.com/2013/01/mindbemding-getting-your-head-round-bem-syntax/
http://nicolasgallagher.com/about-html-semantics-front-end-architecture/

Про stylelint:\
https://habr.com/ru/post/301594/


## SASS/CSS

- BEM - one of most popular (модификация похожа на bulma) четкая
- SMACSS - http://smacss.com/
 автор SMACSS [пишует](https://snook.ca/archives/html_and_css/prefixed-classes-bootstrap), что bootstrap построен на SMACSS
 более 
- OOCSS
- ACSS Atomic


## БЭМ/BEM

```
.block 
.block__element 
.block--modifier
```

## http://nicolasgallagher.com/about-html-semantics-front-end-architecture/
```
/* Utility */
.u-utilityName {}

/* Component */
.ComponentName {}

/* Component modifier */
.ComponentName--modifierName {}

/* Component descendant */
.ComponentName-descendant {}

/* Component descendant modifier */
.ComponentName-descendant--modifierName {}

/* Component state (scoped to component) */
.ComponentName.is-stateOfComponent {}
```
