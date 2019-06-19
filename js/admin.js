$("#AnimalModal").on("show.bs.modal", function(e) {
  var id = $(e.relatedTarget).data("id");
  $("#AnimalModal").data("id", id);
  var name = $(e.relatedTarget).data("name");
  $(e.currentTarget)
    .find('input[id="nameUpdate"]')
    .val(name);
  var age = $(e.relatedTarget).data("age");
  $(e.currentTarget)
    .find('input[id="ageUpdate"]')
    .val(age);
  var gender = $(e.relatedTarget).data("gender");
  $(e.currentTarget)
    .find('input[id="genderUpdate"]')
    .val(gender);
  var breed = $(e.relatedTarget).data("breed");
  $("#breedDropdownButton").text(breed);
  $("#breedDropdownButton").val(breed);
  $("body").on("click", ".dropdown-menu a", function(event) {
    var newBreed = $(this).data("breed");
    $("#breedDropdownButton").text(newBreed);
    $("#breedDropdownButton").val(newBreed);
  });
});
// $('#deleteAnimal').on('click', function() {
//     var id = $(this).data('id');
//     $.ajax({
//             //url: '', // url is empty because I'm working in the same file
//             data: {
//                 action: "deleteAnimal",
//                 id: id,
//             },
//             type: 'POST',
//             success: function(result) {
//                 console.log(result);
//             }
//         });
// });
$("#AnimalModal #saveAnimalUpdate").on("click", function(e) {
  var id = $("#AnimalModal").data("id");
  var newName = $("#nameUpdate").val();
  var newAge = $("#ageUpdate").val();
  var newGender = $("#genderUpdate").val();
  var newBreed = $("#breedDropdownButton").val();
  $.ajax({
    //url: '', // url is empty because I'm working in the same file
    data: {
      action: "updateAnimal",
      id: id,
      newName: newName,
      newAge: newAge,
      newGender: newGender,
      newBreed: newBreed
    },
    type: "POST",
    success: function(result) {
      console.log(result);
    }
  });
  $("#AnimalModal").modal("toggle");
  location.reload();
});
$("#customerModal").on("show.bs.modal", function(e) {
  var id = $(e.relatedTarget).data("id");
  $("#customerModal").data("id", id);
});
$("#customerModal #saveCustomerUpdate").on("click", function(e) {
  var id = $("#customerModal").data("id");
  var newStreet = $("#streetUpdate").val();
  var newPostalCode = $("#postalCodeUpdate").val();
  newPostalCode = newPostalCode.replace(/\s+/g, "");
  var url = "https://geocoder.ca/?postal=" + newPostalCode + "&json=1";
  $.ajax({
    url: url,
    type: "GET"
  }).done(function(result) {
    var newProvince = result.standard.prov;
    var newCity = result.standard.city;
    console.log(newProvince + "  " + newCity);
    $.ajax({
      data: {
        action: "updateCustomerAddress",
        id: id,
        newStreet: newStreet,
        newPostalCode: newPostalCode,
        newProvince: newProvince,
        newCity: newCity
      },
      type: "POST",
      success: function(result) {
        $("#customerModal").modal("toggle");
        location.reload();
      }
    });
  });
});
