function showcost() {
    document.getElementById("cost").value = document.getElementById('serviceslist').value;
}

function sum2() {
    var x = document.getElementById('cost2').value;
    var y = document.getElementById('amount2').value;
    document.getElementById("total2").value = "";
    if (y > 100) {
        var procvalue = 100;
        document.getElementById("amount2").value = procvalue;
    }
    else if (y < 1) {
        var procvalue = 1;
        document.getElementById("amount2").value = procvalue;
    }
    var total = (parseFloat(x) * parseFloat(y)) / 100;
    document.getElementById("total2").value = total;
}