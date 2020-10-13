document.getElementById('select_all')
    .addEventListener('click', function (e) {
        var els = document.querySelectorAll(
            'input[type=checkbox]'
        );

        Array.prototype.forEach.call(els, function(cb){
            cb.checked = e.target.checked;
        });
    })
;