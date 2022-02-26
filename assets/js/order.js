
window.addEventListener('load',()=>{
  
  loadDataServerPageOrders("today");

  on('submit','#formsearchorderstypedata',function(e){
    e.preventDefault();
    let typeData=select('#formsearchorderstypedata #dpworderstypedata').value;
    loadDataServerPageOrders(typeData);
    let text="";
    if(typeData=="today"){
        text="Hoy Día";
    }else if(typeData=="month"){
        text="Este Mes";
    }else{
        text="Este Año";
    }
    select('.info-card #lblorders').innerHTML=text;
    select('.recent-sales .card-body #lblorderstable').innerHTML=text;
  });

  on('submit','#formsearchordersfordate',function(e){
    e.preventDefault();
    let dateInput=select('#formsearchordersfordate #txtdatedata').value;
    loadDataServerPageOrders(dateInput);
    select('.info-card #lblorders').innerHTML=dateInput;
    select('.recent-sales .card-body #lblorderstable').innerHTML=dateInput;
  });

  on('click','#btnreloaddataorders',function(e){
    e.preventDefault();
    let typeData=select('#formsearchorderstypedata #dpworderstypedata').value;
    loadDataServerPageOrders(typeData);
    let text="";
    if(typeData=="today"){
        text="Hoy Día";
    }else if(typeData=="month"){
        text="Este Mes";
    }else{
        text="Este Año";
    }
    select('.info-card #lblorders').innerHTML=text;
    select('.recent-sales .card-body #lblorderstable').innerHTML=text;
  });

});