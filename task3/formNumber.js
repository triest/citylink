function sum2() {
    var x = document.getElementById('inputNumber').value;
    var y = document.getElementById('procent').value;
    document.getElementById("answer").value = "";
    if (y > 100) {
        var procvalue = 100;
        document.getElementById("procent").value = procvalue;
    }
    else if (y < 1) {
        var procvalue = 1;
        document.getElementById("procent").value = procvalue;
    }
    var total = (parseFloat(x) * parseFloat(y)) / 100;
    document.getElementById("answer").value = total;
}