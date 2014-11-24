jQuery.fn.anchor = function(options) {
    options = $.extend(
        {},
        {
            'selector': 'H1, H2, H3, H4, H5',
            'className': 'anchor',
            'content': '§',
            'position': 'prepend'
        },
        options
    );
    this.find(options['selector']).each(function(index, element) {
        $element = $(element);
        if (!$element.attr('id')) {
            $element.attr('id', $element.text().toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,''));
        }
        $element[options['position']]('<a class="' + options['className'] + '" href="#' + $element.attr('id') + '">' + options['content'] + '</a>');
    });
};
