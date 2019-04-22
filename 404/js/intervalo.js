//counting from 5 to 0
var counter = 6
setInterval(() =>{
    if(counter >= 1){
        counter--
        document.getElementById('segundos').innerText = counter
    }
},1000)