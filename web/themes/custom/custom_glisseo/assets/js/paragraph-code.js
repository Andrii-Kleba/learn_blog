/**
 * @file
 * Paragraphs code highlight behaviors.
 */

(function ($, Drupal) {

  Drupal.behaviors.paragraphCodeHighlight = {
    attach: function (context, settings) {
      const $wrap_elements = $(context).find('div.paragraph-code-field-contains__value').wrap('<pre>').wrap('<code>');
      const elements = context.querySelectorAll('pre code .paragraph-code-field-contains__value');

      elements.forEach(element => {
        hljs.highlightBlock(element);
      })
    }
  };

})(jQuery, Drupal);
