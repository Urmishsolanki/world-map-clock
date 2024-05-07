<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span id="year"></span><span> Education International </span></strong> - All rights reserved.
  </div>
</footer>
<script>
  const d = new Date();
  document.getElementById("year").innerHTML = d.getFullYear();
</script>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script src="{{ URL::asset('public/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>