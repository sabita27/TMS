    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-message');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500); // Wait for transition
                }, 5000); // 5 seconds
            });
        });
    </script>
</body>
</html>
