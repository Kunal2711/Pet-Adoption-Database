// sup
// function SearchFunction() {
//     var input, filter, tr, data, i, txtValue;
//     input = document.getElementById("searchInput");
//     filter = input.value.toLowerCase();
//     cards = document.getElementsByClassName("animal-card");
//     row = document.getElementsByClassName("animal-content");
//     for (i = 0; i < row.length; i++) {
//         for (j = 0; j < row[i].children.length; j++) {
//             txtValue = row[i].children[j].innerText.toLowerCase();
//             if (txtValue.includes(filter)) {
//                 cards[i].style.display = "";
//                 break;
//             } else {
//                 cards[i].style.display = "none";
//             }
//         }
//     }
// }

// function dropdownSearch(x1) {
//     // TODO: Debug, check the following link for resource: 
//     // https://www.w3schools.com/jsref/event_onchange.asp
//     var filter, i, txtValue;
//     filter = document.getElementById(x1 + 'Select').value;
//     cards = document.getElementsByClassName("animal-card");
//     row = document.getElementsByClassName('animal-' + x1.toLowerCase());
//     if (filter.includes("Gender") || filter.includes("Breed") || filter.includes("Type")) {
//         for (i = 0; i < row.length; i++) {
//             cards[i].style.display = "";
//         }
//     } else {
//         for (i = 0; i < row.length; i++) {
//             txtValue = row[i].innerText.toLowerCase();
//             var matchstring = txtValue.split(':');
//             // console.log(matchstring);
//             if (matchstring[1].trim() == filter.toLowerCase()) {
//                 cards[i].style.display = "";
//             } else {
//                 cards[i].style.display = "none";
//             }
//         }
//     }
// }
