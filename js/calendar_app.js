$reservationForm = $("#reservations");

function activate(e) {

  console.log("activate");

    var $wrapper = $(e.currentTarget).parent();
    // console.log($wrapper);
    $wrapper
      .addClass('active')
      .siblings().addClass('inactive');
  }

  function dismiss(e) {

    if($(e.currentTarget).hasClass("dismiss")){
      $reservationForm.hide();
      var $wrapper = $(e.currentTarget).closest('li');
      console.log("dismiss");


      $wrapper
        .removeClass('active')
        .siblings().removeClass('inactive');
      e.stopPropagation();
    }
  }

  function checkKey(e) {
    var $wrapper = $(e.currentTarget).parent();
    var isActive = $wrapper.hasClass('active');
    $reservationForm.hide();
    console.log("checkey");

    // console.log($wrapper);
    if (isActive && (e.keyCode === 13 || e.keyCode === 27)) {
      // active and hit enter or escape
      dismiss(e);
    } else if (!isActive && e.keyCode === 13) {
      // not active and hit enter
      activate(e);
    }
  }

  $('article').on({
    'click': activate,
    'blur': dismiss,
    'keyup': checkKey
  });
  $('.dismiss').on('click', dismiss);


$('.calendar-container td').on('click', function(){

    let $li = $(this).closest("li");
    let isReserved = $(this).hasClass('is-reserved') ? true : false;

    if($li.hasClass("active")){


      console.log("jour jour");
      $reservationForm.show();

      var month = $(this).closest("li").find("h1").text();
      var day = $(this).children(".day").text();

      let $inputMonth = $(this).closest("li").find("h1");
      console.log($inputMonth.attr("id"));
      $("#reservationMonth").attr("value", $inputMonth.attr("id"));


      $("#reservationDay").attr("value", day);

      // console.log($(this).closest("li").find("h1").text());

      $('#reservation-title').text("Réservation pour le " + day + " " + month);

    }

});