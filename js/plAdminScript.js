
(function ($) {
    $('create-project').on('submit', (e) => {
        e.preventDefault();

    });

    // const qpUrl = `${plUrl}/project/create?` 
    $('#create-quick-project').attr('href', localVars.qpUrl);
    
})(this.jQuery);