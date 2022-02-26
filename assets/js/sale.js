
window.addEventListener('load',()=>{
  
  loadDataServerSale("today");
  //setInterval(() => { loadDataServerSale("today"); }, 90000);
  on('submit','#formsearchtypedata',function(e){
    e.preventDefault();
    let typeData=select('#formsearchtypedata #dpwtypedata').value;
    loadDataServerSale(typeData);
    let text="";
    if(typeData=="today"){
        text="Hoy Día";
    }else if(typeData=="month"){
        text="Este Mes";
    }else{
        text="Este Año";
    }
    select('.sales-card #lblsales').innerHTML=text;
    select('.revenue-card #lblincome').innerHTML=text;
    select('.recent-sales .card-body #lblsalestable').innerHTML=text;
  });

  on('submit','#formsearchfordate',function(e){
    e.preventDefault();
    let dateInput=select('#formsearchfordate #txtdatedata').value;
    loadDataServerSale(dateInput);
    select('.sales-card #lblsales').innerHTML=dateInput;
    select('.revenue-card #lblincome').innerHTML=dateInput;
    select('.recent-sales .card-body #lblsalestable').innerHTML=dateInput;
  });

  on('click','#btnreloaddatasale',function(e){
    e.preventDefault();
    let typeData=select('#formsearchtypedata #dpwtypedata').value;
    loadDataServerSale(typeData);
    let text="";
    if(typeData=="today"){
        text="Hoy Día";
    }else if(typeData=="month"){
        text="Este Mes";
    }else{
        text="Este Año";
    }
    select('.sales-card #lblsales').innerHTML=text;
    select('.revenue-card #lblincome').innerHTML=text;
    select('.recent-sales .card-body #lblsalestable').innerHTML=text;
  });

});