    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset ('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset ('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset ('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset ('sbadmin2/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('sbadmin2/js/demo/datatables-demo.js') }}"></script>

    <!-- Load SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@if(session('success'))
<script>
Swal.fire({
    title: "Success!",
    text: "{{ session('success') }}",
    icon: "success"
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    title: "Failed!",
    text: "{{ session('error') }}",
    icon: "error"
});
</script>
@endif

</body>

</html>