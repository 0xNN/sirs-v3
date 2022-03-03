<script type="text/javascript">
  $(document).ready(function () {
    var isActiveNav = localStorage.getItem("isNavActive");
    if(isActiveNav != null) {
      if(isActiveNav == 'covid') {
        $('.nav-covid').removeClass('active').addClass('active');
        $('.nav-diagnosa').removeClass('active');
        $('.nav-komorbid').removeClass('active');
        $('.nav-terapi').removeClass('active');
        $('.nav-vaksinasi').removeClass('active');
        $('.nav-statuskeluar').removeClass('active');
        $('.nav-penjelasan').removeClass('active');
        $('.tab-covid').removeClass('active').addClass('active');
        $('.tab-diagnosa').removeClass('active');
        $('.tab-komorbid').removeClass('active');
        $('.tab-terapi').removeClass('active');
        $('.tab-vaksinasi').removeClass('active');
        $('.tab-statuskeluar').removeClass('active');
        $('.tab-penjelasan').removeClass('active');
      }
      if(isActiveNav == 'diagnosa') {
        $('.nav-covid').removeClass('active');
        $('.nav-diagnosa').removeClass('active').addClass('active');
        $('.nav-komorbid').removeClass('active');
        $('.nav-terapi').removeClass('active');
        $('.nav-vaksinasi').removeClass('active');
        $('.nav-statuskeluar').removeClass('active');
        $('.nav-penjelasan').removeClass('active');
        $('.tab-covid').removeClass('active');
        $('.tab-diagnosa').removeClass('active').addClass('active');
        $('.tab-komorbid').removeClass('active');
        $('.tab-terapi').removeClass('active');
        $('.tab-vaksinasi').removeClass('active');
        $('.tab-statuskeluar').removeClass('active');
        $('.tab-penjelasan').removeClass('active');
      }
      if(isActiveNav == 'komorbid') {
        $('.nav-covid').removeClass('active');
        $('.nav-diagnosa').removeClass('active');
        $('.nav-komorbid').removeClass('active').addClass('active');
        $('.nav-terapi').removeClass('active');
        $('.nav-vaksinasi').removeClass('active');
        $('.nav-statuskeluar').removeClass('active');
        $('.nav-penjelasan').removeClass('active');
        $('.tab-covid').removeClass('active');
        $('.tab-diagnosa').removeClass('active');
        $('.tab-komorbid').removeClass('active').addClass('active');
        $('.tab-terapi').removeClass('active');
        $('.tab-vaksinasi').removeClass('active');
        $('.tab-statuskeluar').removeClass('active');
        $('.tab-penjelasan').removeClass('active');
      }
      if(isActiveNav == 'terapi') {
        $('.nav-covid').removeClass('active');
        $('.nav-diagnosa').removeClass('active');
        $('.nav-komorbid').removeClass('active');
        $('.nav-terapi').removeClass('active').addClass('active');
        $('.nav-vaksinasi').removeClass('active');
        $('.nav-statuskeluar').removeClass('active');
        $('.nav-penjelasan').removeClass('active');
        $('.tab-covid').removeClass('active');
        $('.tab-diagnosa').removeClass('active');
        $('.tab-komorbid').removeClass('active');
        $('.tab-terapi').removeClass('active').addClass('active');
        $('.tab-vaksinasi').removeClass('active');
        $('.tab-statuskeluar').removeClass('active');
        $('.tab-penjelasan').removeClass('active');
      }
      if(isActiveNav == 'vaksinasi') {
        $('.nav-covid').removeClass('active');
        $('.nav-diagnosa').removeClass('active');
        $('.nav-komorbid').removeClass('active');
        $('.nav-terapi').removeClass('active');
        $('.nav-vaksinasi').removeClass('active').addClass('active');
        $('.nav-statuskeluar').removeClass('active');
        $('.nav-penjelasan').removeClass('active');
        $('.tab-covid').removeClass('active');
        $('.tab-diagnosa').removeClass('active');
        $('.tab-komorbid').removeClass('active');
        $('.tab-terapi').removeClass('active');
        $('.tab-vaksinasi').removeClass('active').addClass('active');
        $('.tab-statuskeluar').removeClass('active');
        $('.tab-penjelasan').removeClass('active');
      }
      if(isActiveNav == 'statuskeluar') {
        $('.nav-covid').removeClass('active');
        $('.nav-diagnosa').removeClass('active');
        $('.nav-komorbid').removeClass('active');
        $('.nav-terapi').removeClass('active');
        $('.nav-vaksinasi').removeClass('active');
        $('.nav-statuskeluar').removeClass('active').addClass('active');
        $('.nav-penjelasan').removeClass('active');
        $('.tab-covid').removeClass('active');
        $('.tab-diagnosa').removeClass('active');
        $('.tab-komorbid').removeClass('active');
        $('.tab-terapi').removeClass('active');
        $('.tab-vaksinasi').removeClass('active');
        $('.tab-statuskeluar').removeClass('active').addClass('active');
        $('.tab-penjelasan').removeClass('active');
      }
      if(isActiveNav == 'penjelasan') {
        $('.nav-covid').removeClass('active');
        $('.nav-diagnosa').removeClass('active');
        $('.nav-komorbid').removeClass('active');
        $('.nav-terapi').removeClass('active');
        $('.nav-vaksinasi').removeClass('active');
        $('.nav-statuskeluar').removeClass('active');
        $('.nav-penjelasan').removeClass('active').addClass('active');
        $('.tab-covid').removeClass('active');
        $('.tab-diagnosa').removeClass('active');
        $('.tab-komorbid').removeClass('active');
        $('.tab-terapi').removeClass('active');
        $('.tab-vaksinasi').removeClass('active');
        $('.tab-statuskeluar').removeClass('active');
        $('.tab-penjelasan').removeClass('active').addClass('active');
      }
    } else {
      localStorage.setItem('isNavActive', 'covid');
    }
    $('.nav-covid').click(function() {
      localStorage.setItem('isNavActive', 'covid');
    });
    $('.nav-diagnosa').click(function() {
      localStorage.setItem('isNavActive', 'diagnosa');
    });
    $('.nav-komorbid').click(function() {
      localStorage.setItem('isNavActive', 'komorbid');
    });
    $('.nav-terapi').click(function() {
      localStorage.setItem('isNavActive', 'terapi');
    });
    $('.nav-vaksinasi').click(function() {
      localStorage.setItem('isNavActive', 'vaksinasi');
    });
    $('.nav-statuskeluar').click(function() {
      localStorage.setItem('isNavActive', 'statuskeluar');
    });
    $('.nav-penjelasan').click(function() {
      localStorage.setItem('isNavActive', 'penjelasan');
    });
  });
</script>