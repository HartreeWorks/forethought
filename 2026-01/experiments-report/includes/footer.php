    </main>
    <script>
    document.querySelectorAll('h1, h2, h3').forEach(function(h) {
        if (h.closest('a')) return;
        if (!h.id) {
            var base = h.textContent.trim()
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            var id = base;
            var n = 1;
            while (document.getElementById(id)) {
                id = base + '-' + n;
                n++;
            }
            h.id = id;
        }
        var a = document.createElement('a');
        a.href = '#' + h.id;
        a.className = 'anchor-link';
        a.textContent = '#';
        a.addEventListener('click', function(e) {
            e.preventDefault();
            history.replaceState(null, '', '#' + h.id);
            navigator.clipboard.writeText(window.location.href);
        });
        h.insertBefore(a, h.firstChild);
    });
    </script>
</body>
</html>
