window.onload = function() {
    // Znajduje elementy z klasą .gb-is-root-block > .gb-container
    var elements = document.querySelectorAll('.gb-is-root-block > .gb-containe > .gb-container');
    
    // Iteruje przez wszystkie znalezione elementy i dodaje data attribute
    elements.forEach(function(element) {
        element.setAttribute('data-gb-width', 'blocksy-width');
    });
};
