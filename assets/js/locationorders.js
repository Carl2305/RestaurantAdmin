var dataAllBoards=[];
const loadDataSelectTable=()=>{
  let formData= new FormData();
  formData.append('type','listboards');
  fetch('assets/vendor/access-data/db-server-app.php',{
    method:'POST',
    body: formData,
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  }).then(response=>{
    if(response.ok){
      return response.text();
    }else{
      throw new Error(`${response.status} ${response.statusText} ${response.url}`); 
    }
  }).then(data=>{
    if(JSON.parse(data).length>0){
      dataAllBoards=JSON.parse(data.trim());
      codeHTML='<option value="NN">Seleccionar Mesa</option>';
      dataAllBoards.forEach(function (item,index) {
        if(item.status_board==0){
          codeHTML+=`<option value="${item.id_board}" data-capacity="${item.capacity_board}">MESA ${item.code_board}</option>`;
        }       
      });
      select('#nametable').innerHTML=codeHTML;
    }else{
      dataAllBoards=[];
      select('#nametable').innerHTML='<option value="NN">Seleccionar Mesa</option>';
      select('#nameboard').textContent='';
      select('#capacityboard').textContent='0';
    }
  }).catch((error)=>{
    console.error(error.message);
    select('#nametable').innerHTML='<option value="NN">Seleccionar Mesa</option>';
    select('#nameboard').textContent='';
    select('#capacityboard').textContent='0';
  });
}

var dataDishAll=[];
const loadItemsdishLocalOrder = (text="") => {
  if(text!=null){
    let formData=new FormData();
    formData.append('type','searchdishlocal');
    formData.append('textsearch',text);
    fetch('assets/vendor/access-data/db-server-app.php',{
      method:'POST',
      body: formData,
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    }).then(response=>{
      if(response.ok){
        return response.text();
      }else{
        throw new Error(`${response.status} ${response.statusText} ${response.url}`); 
      }
    }).then(data=>{
      if(JSON.parse(data).length>0){
        dataDishAll=JSON.parse(data.trim());
        select('#itemsdish').innerHTML="";
        select('#msgerrorlist').classList.add('d-none');
        printItemsLocalOrder(JSON.parse(data));
      }else{
        dataDishAll=[];
        select('#itemsdish').innerHTML="";
        select('#msgerrorlist').classList.remove('d-none');
      }
    }).catch((error)=>{
      console.error(error.message);
    });
  }
}

const printItemsLocalOrder = data => {
  let codeHTML="";
  data.forEach(function(item,index){
      codeHTML+=`<div class="col-6 d-flex justify-content-center">
      <div class="card w-100">
        <div class="card-body w-100">
          <br>
          <div class="d-flex align-items-center">
            <div class="card-icon d-flex align-items-center justify-content-center">
              <img src="${item.url_image_dish}" alt="${item.name_dish}" class="w-100 rounded-circle">
            </div>
            <div class="ps-3 w-100">
              <div class="d-flex justify-content-between">
                <h5>${item.name_dish}</h5>
                <h5 class="ps-2 fw-bold">${new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(item.price_dish)}</h5>
              </div>
              <p>${item.description_dish}</p>
              <h6 class="fw-bold">${item.name_category}</h6>
              <div class="d-flex justify-content-end">
                <button class="btn btn-success btn-sm btnaddcart" onclick="checkSish('${item.code_dish}',${item.id_dish})">Agregar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>`;
  });
  select('#itemsdish').innerHTML=codeHTML;
}

var dishSelected  = [];
if(localStorage.getItem("dishselectd")){
  dishSelected=JSON.parse(localStorage.getItem("dishselectd"));
}else{
  dishSelected=[];
}
if(localStorage.getItem("boardselected")){
  if(localStorage.getItem("nameboardselected")){
    select('#capacityboard').textContent=localStorage.getItem("boardselected");
    select('#nameboard').textContent=localStorage.getItem("nameboardselected");
  }  
}else{
  select('#nameboard').textContent='';
  select('#capacityboard').textContent='0';
}

const saveLocalStorage=(code, id,url,name,descrip,price,comment)=>{
  
  dishSelected.push({ 
    "codigo"    : code.trim(),
    "id"  : id,
    "imagen"  : url.trim(),
    "nombre"    : name.trim(), 
    "descripcion"  : descrip.trim(),
    "precio"    : price.trim(),
    "comentario"  : comment.trim(),
    "cantidad"  : 1
  });

  //let hash = {};
  //datos = datos.filter(o => hash[o.codigo] ? o.cantidad=o.cantidad+1 : hash[o.codigo] = true);
  
  localStorage.setItem("dishselectd",JSON.stringify(dishSelected));
  //console.log(datos)

  loadResumeOrderLocal();
}

const checkSish = (code, id) => {
  Swal.fire({
    title: "Comentario Adicional",
    text: "Este espacio es para escribir algun comentario sobre tu plato antes de hacer tu pedido",
    input: "textarea",
    inputPlaceholder: "Escribe aquí...",
    inputAttributes: {
      maxlength: 200,
      autocapitalize: 'off',
      autocorrect: 'off'
    },
    showCancelButton: true,
    confirmButtonText: "Enviar Comentario",
    confirmButtonColor:'#cda45e',
    cancelButtonText: "Cancelar",
    allowOutsideClick: false/*,
      inputValidator: (value) => {
        if (!value) {
          return 'Ingrese un comentario adicional.'
        }
      }*/
  }).then(result => {
      //if (result.value) {
        comment_menu=result.value;
        dataDishAll.forEach(function(item,index){
          if(item.id_dish==id && item.code_dish==code){
            saveLocalStorage(code, id, item.url_image_dish, item.name_dish, item.description_dish, item.price_dish, comment_menu); 
          }
        });        
      //}
  });
}

const loadResumeOrderLocal=()=>{
  let codeHTML="";
  let total=0.0;
  select('#listdishesselected').innerHTML="";
  if(dishSelected.length>0){
    select('#msgerrorselect').classList.add('d-none');
    select('#btnsaveorderdishselect').classList.remove('d-none');
    dishSelected.forEach(function (item, index) {
      total+=parseFloat(item.precio);
      codeHTML+=`<div class="card" id="${item.codigo}">
        <div class="card-body p-0">
          <div class="col-12 position-relative">
            <button class="btn btn-danger position-absolute top-0 end-0" onclick="removeDishSelected('${item.codigo}',${item.id})"><i class="bi bi-trash"></i></button>
          </div>
          <br>
          <div class="text-center p-1">
            <h6 class="fw-bold">${item.nombre}</h6>
            <p class="text-start">${item.descripcion}</p>`;
            if(item.comentario==''){
              codeHTML+=`</div>
                </div>
              </div>`;
            }else{
              codeHTML+=`<p class="breadcrumb-item text-start">Comentario: ${item.comentario}</p>
                  </div>
                </div>
              </div>`;
            }
    });
    select('#totalorderdishlocal').textContent=new Intl.NumberFormat("es-PE",{style: "currency", currency: "PEN"}).format(total);
    select('#listdishesselected').innerHTML=codeHTML;
  }else{
    select('#msgerrorselect').classList.remove('d-none');
    select('#totalorderdishlocal').textContent='S/ 00.00';
    select('#btnsaveorderdishselect').classList.add('d-none');
  }
}

const removeDishSelected = (code, dish) => {
    dishSelected.forEach(function(item, index, obj){
      if(item.codigo===code && item.id==dish){
        obj.splice(index, 1);
      }
    });
    localStorage.setItem("dishselectd",JSON.stringify(dishSelected));
    select(`#${code}`).innerHTML="";
    select(`#${code}`).classList.add('d-none');
    loadResumeOrderLocal();
}

const saveOrderLocal=()=>{
  let board=localStorage.getItem("boardselected");
  let codeboard=localStorage.getItem("nameboardselected");
  let formData=new FormData();
  formData.append('type', 'savelocalorder');
  formData.append('board', board);
  formData.append('codboard', codeboard);
  formData.append('dataorder', JSON.stringify(dishSelected));
  fetch('assets/vendor/access-data/db-server-app.php',{
    method:'POST',
    body: formData,
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  }).then(response=>{
    if(response.ok){
      return response.text();
    }else{
      throw new Error(`${response.status} ${response.statusText} ${response.url}`); 
    }
  }).then(data=>{
    console.log(data);
    if(data.trim()==0){
      Swal.fire({
        title:'Error',
        icon:'error'
      });
    }else if(data.trim()==1){
      Swal.fire({
        title: 'Pedido Registrado',
        icon:'success',
        confirmButtonText: "OK",
        confirmButtonColor:'#0dcaf0',
        allowOutsideClick: false
      }).then((result) => {
        if (result.isConfirmed) {
          localStorage.removeItem("dishselectd");
          localStorage.removeItem("nameboardselected");
          localStorage.removeItem("boardselected");
          dishSelected=[];
          loadResumeOrderLocal();
          select('#nameboard').textContent='';
          select('#capacityboard').textContent='0';
        }
      });
    }
  }).catch((error)=>{
    console.error(error.message);
  });
}

window.addEventListener('load',()=>{

  try {
    loadItemsdishLocalOrder();
  } catch (error) { console.error(error.message); }

  try {
    on('keyup','#search',function(e){
      loadItemsdishLocalOrder(this.value);
    });
  }  catch (error) { console.error(error.message); }

  try {
    loadDataSelectTable();
    setInterval(()=>{loadDataSelectTable();},10000);
  } catch (error) { console.error(error.message); }

  try {
    on('click','#btnselectedboard',function(e){
      let board=select('#nametable').value;
      if(dataAllBoards.length>0){
        dataAllBoards.forEach(function(item,index){
          if(item.id_board==board){
            select('#capacityboard').textContent=item.capacity_board;
            select('#nameboard').textContent=item.code_board;
            localStorage.setItem("boardselected",board);
            localStorage.setItem("nameboardselected",item.code_board);
          }
        });
      }else{
        select('#nameboard').textContent='';
        select('#capacityboard').textContent='0';
      }
    });
  } catch (error) { console.error(error.message); }

  try {
    loadResumeOrderLocal();
  } catch (error) { console.error(error.message); }

  on('click','#btnsaveorderdishselect',function (e) {
    if(localStorage.getItem("boardselected")){
      if(localStorage.getItem("nameboardselected")){
        saveOrderLocal();
      }else{
        Swal.fire({
          title:'Error',
          text:'Seleccione una Mesa',
          icon:'error'
        });
      }
    }else{
      Swal.fire({
        title:'Error',
        text:'Seleccione una Mesa',
        icon:'error'
      });
    }
  });
  
});