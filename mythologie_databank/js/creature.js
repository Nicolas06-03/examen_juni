fetch("../php/input_aanloggegevens.php")
.then(result => result.json())
.then(data => {
    console.log(data);
    data.forEach((gebruiker) =>{})
})
