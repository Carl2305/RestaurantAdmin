window.addEventListener('load',()=>{
  // carga la data por primera vez
  loadDataServerPageHome();
  // actualiza la data cada 1.5min
  setInterval(() => { loadDataServerPageHome(); }, 90000);

  // actuliza la data cuando se presiona el boton de actualizar
  on('click','#btnreloaddatahome',function(e){
    e.preventDefault();
    loadDataServerPageHome();
  });
  on('click','#btn-check-order',function(e){
    e.preventDefault();
    let codeOrder=this.dataset.ordercheck;    
    markOrder(codeOrder,'check');
  });

});