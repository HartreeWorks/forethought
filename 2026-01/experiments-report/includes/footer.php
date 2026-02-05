    </main>
    <script>
    document.querySelectorAll('h1[id], h2[id], h3[id]').forEach(function(h) {
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
