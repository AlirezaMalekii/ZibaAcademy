const registernumberaddition = () =>{
    const oldvalue = parseInt(document.getElementById("register-number").innerHTML);
    const newvalue = oldvalue + 1;
    document.getElementById("register-number").innerHTML = newvalue; 
}
const registernumberminus = () =>{
    const oldvalue = parseInt(document.getElementById("register-number").innerHTML);
    const newvalue = oldvalue - 1;
    if (newvalue < 0){
        newvalue = oldvalue;
    }
    document.getElementById("register-number").innerHTML = newvalue; 
}