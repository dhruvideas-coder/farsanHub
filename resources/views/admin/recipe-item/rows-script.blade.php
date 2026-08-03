<script>
(function () {
    var container = document.getElementById('material-rows');
    var template  = document.getElementById('row-template');
    var addBtn    = document.getElementById('add-row');

    function renumber() {
        container.querySelectorAll('.material-row').forEach(function (row, i) {
            var idx = row.querySelector('.idx');
            if (idx) idx.textContent = i + 1;
        });
    }

    function addRow(focus) {
        var clone = template.content.firstElementChild.cloneNode(true);
        container.appendChild(clone);
        renumber();
        if (focus) {
            var input = clone.querySelector('input[name="material_name[]"]');
            if (input) input.focus();
        }
    }

    addBtn.addEventListener('click', function () { addRow(true); });

    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-row');
        if (!btn) return;
        var rows = container.querySelectorAll('.material-row');
        if (rows.length > 1) {
            btn.closest('.material-row').remove();
        } else {
            // Keep at least one row; just clear it.
            var row = btn.closest('.material-row');
            row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
        }
        renumber();
    });

    // Ensure there is always at least one row on load.
    if (container.querySelectorAll('.material-row').length === 0) {
        addRow(false);
    }
})();
</script>
