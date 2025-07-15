jQuery(document).ready(function($){
    function updateIndices(container){
        container.children('.fancy-helpers-field').each(function(i){
            $(this).find('input, textarea, label').each(function(){
                ['name','id','for'].forEach(attr=>{
                    var val=$(this).attr(attr);
                    if(val){
                        val=val.replace(/_(\d+)/g,'_'+i);
                        val=val.replace(/\[(\d+)\]/g,'['+i+']');
                        $(this).attr(attr,val);
                    }
                });
            });
        });
    }
    function initContainer(selector){
        var c=$(selector);
        if(!c.length) return;
        c.sortable({handle:'.drag-handle',update:function(){updateIndices(c);}});
        var observer=new MutationObserver(function(){
            updateIndices(c);
            c.sortable('refresh');
        });
        observer.observe(c[0],{childList:true});
        updateIndices(c);
    }
    initContainer('#fancy-helpers-addresses-container');
    initContainer('#fancy-helpers-phones-container');
    initContainer('#fancy-helpers-emails-container');
    initContainer('#fancy-helpers-social-media-container');
    initContainer('#fancy-helpers-customs-container');
    initContainer('#fancy-helpers-contacts-groups-container');
});
