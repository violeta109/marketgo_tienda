//let carrito=JSON.parse(localStorage.getItem("carrito"))||[];
//const grid=document.getElementById("gridProductos"),cat=document.getElementById("categoria"),buscar=document.getElementById("buscar"),orden=document.getElementById("orden"),categoriasMenu=document.getElementById("categoriasMenu");
//const categorias=[...new Set(productos.map(p=>p.categoria))];
//categorias.forEach(c=>{cat.innerHTML+=`<option value="${c}">${c}</option>`;categoriasMenu.innerHTML+=`<button class="cat-btn" onclick="filtrarCategoria('${c}')">${iconoCategoria(c)} ${c}</button>`});
let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
let productos = [];

const grid = document.getElementById("gridProductos"),
      cat = document.getElementById("categoria"),
      buscar = document.getElementById("buscar"),
      orden = document.getElementById("orden"),
      categoriasMenu = document.getElementById("categoriasMenu");
function iconoCategoria(c){return({"Abarrotes":"🛒","Bebidas":"🥤","Limpieza":"🧼","Cuidado personal":"🧴","Snacks":"🍪","Lácteos":"🥛","Frutas":"🍎","Verduras":"🥦","Carnes":"🥩","Panadería":"🥐"}[c]||"🛍️")}
function imagenProducto(p){
  const imagenes = {
    "Arroz Costeño 1kg":"https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=500&q=80",
    "Azúcar Rubia 1kg":"https://images.unsplash.com/photo-1615485500704-8e990f9900f7?auto=format&fit=crop&w=500&q=80",
    "Fideos Spaghetti":"https://images.unsplash.com/photo-1551462147-ff29053bfc14?auto=format&fit=crop&w=500&q=80",
    "Aceite Vegetal 1L":"https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=500&q=80",
    "Lentejas 500g":"https://images.unsplash.com/photo-1515543904379-3d757afe72e4?auto=format&fit=crop&w=500&q=80",
    "Atún en lata":"img/atun.jpg",
    "Sal de mesa":"https://images.unsplash.com/photo-1518110925495-5fe2fda0442c?auto=format&fit=crop&w=500&q=80",
    "Harina preparada":"https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=500&q=80",
    "Avena 500g":"https://images.unsplash.com/photo-1614961233913-a5113a4a34ed?auto=format&fit=crop&w=500&q=80",
    "Quinua 500g":"https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&w=500&q=80",

    "Agua Mineral":"https://images.unsplash.com/photo-1548839140-29a749e1cf4d?auto=format&fit=crop&w=500&q=80",
    "Gaseosa Cola":"https://images.unsplash.com/photo-1581636625402-29b2a704ef13?auto=format&fit=crop&w=500&q=80",
    "Jugo de Naranja":"https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=500&q=80",
    "Energizante":"https://images.unsplash.com/photo-1622543925917-763c34d1a86e?auto=format&fit=crop&w=500&q=80",
    "Té Helado":"https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=500&q=80",
    "Café Instantáneo":"https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=500&q=80",
    "Leche Chocolatada":"https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=500&q=80",
    "Bebida Rehidratante":"https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=500&q=80",
    "Agua con gas":"img/botellagas.jpg",
    "Refresco de Maracuyá":"https://images.unsplash.com/photo-1546173159-315724a31696?auto=format&fit=crop&w=500&q=80",

    "Detergente 1kg":"https://images.unsplash.com/photo-1585421514738-01798e348b17?auto=format&fit=crop&w=500&q=80",
    "Lejía 1L":"https://images.unsplash.com/photo-1583947581924-860bda6a26df?auto=format&fit=crop&w=500&q=80",
    "Lavavajilla":"https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?auto=format&fit=crop&w=500&q=80",
    "Suavizante":"https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?auto=format&fit=crop&w=500&q=80",
    "Limpiatodo":"https://images.unsplash.com/photo-1563453392212-326f5e854473?auto=format&fit=crop&w=500&q=80",
    "Desinfectante":"https://images.unsplash.com/photo-1584483766114-2cea6facdf57?auto=format&fit=crop&w=500&q=80",
    "Papel Toalla":"https://images.unsplash.com/photo-1584556812952-905ffd0c611a?auto=format&fit=crop&w=500&q=80",
    "Esponjas x3":"https://images.unsplash.com/photo-1628177142898-93e36e4e3a50?auto=format&fit=crop&w=500&q=80",
    "Bolsa Basura":"https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&w=500&q=80",
    "Ambientador":"https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=500&q=80",

    "Shampoo":"https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?auto=format&fit=crop&w=500&q=80",
    "Jabón Tocador":"https://images.unsplash.com/photo-1607006344380-b6775a0824a7?auto=format&fit=crop&w=500&q=80",
    "Pasta Dental":"https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=500&q=80",
    "Cepillo Dental":"https://images.unsplash.com/photo-1606811971618-4486d14f3f99?auto=format&fit=crop&w=500&q=80",
    "Desodorante":"https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=500&q=80",
    "Papel Higiénico":"https://images.unsplash.com/photo-1584556812952-905ffd0c611a?auto=format&fit=crop&w=500&q=80",
    "Crema Corporal":"https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=500&q=80",
    "Gel antibacterial":"https://images.unsplash.com/photo-1584483766114-2cea6facdf57?auto=format&fit=crop&w=500&q=80",
    "Acondicionador":"https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?auto=format&fit=crop&w=500&q=80",
    "Talco":"https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=500&q=80",

    "Papas Fritas":"https://images.unsplash.com/photo-1566478989037-eec170784d0b?auto=format&fit=crop&w=500&q=80",
    "Galletas Chocolate":"https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=500&q=80",
    "Chocolate Barra":"https://images.unsplash.com/photo-1511381939415-e44015466834?auto=format&fit=crop&w=500&q=80",
    "Maní Salado":"https://images.unsplash.com/photo-1567892737950-30c4db37cd89?auto=format&fit=crop&w=500&q=80",
    "Cereal Bar":"img/cerealbar.jpg",
    "Chifles":"https://images.unsplash.com/photo-1621447504864-d8686e12698c?auto=format&fit=crop&w=500&q=80",
    "Gomitas":"https://images.unsplash.com/photo-1582058091505-f87a2e55a40f?auto=format&fit=crop&w=500&q=80",
    "Nachos":"https://images.unsplash.com/photo-1513456852971-30c0b8199d4d?auto=format&fit=crop&w=500&q=80",
    "Pop Corn":"https://images.unsplash.com/photo-1578849278619-e73505e9610f?auto=format&fit=crop&w=500&q=80",
    "Galleta Soda":"https://images.unsplash.com/photo-1558961363-fa8fdf82db35?auto=format&fit=crop&w=500&q=80",

    "Leche Evaporada":"https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=500&q=80",
    "Yogurt Fresa":"https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=500&q=80",
    "Queso Fresco":"https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=500&q=80",
    "Mantequilla":"https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?auto=format&fit=crop&w=500&q=80",
    "Leche UHT":"https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=500&q=80",
    "Yogurt Natural":"https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=500&q=80",
    "Queso Edam":"https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=500&q=80",
    "Crema de Leche":"https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=500&q=80",
    "Manjar Blanco":"https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=500&q=80",
    "Helado Vainilla":"https://images.unsplash.com/photo-1567206563064-6f60f40a2b57?auto=format&fit=crop&w=500&q=80",

    "Manzana Roja kg":"https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=500&q=80",
    "Plátano kg":"https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=500&q=80",
    "Naranja kg":"https://images.unsplash.com/photo-1580052614034-c55d20bfee3b?auto=format&fit=crop&w=500&q=80",
    "Uva kg":"https://images.unsplash.com/photo-1537640538966-79f369143f8f?auto=format&fit=crop&w=500&q=80",
    "Papaya kg":"https://images.unsplash.com/photo-1617112848923-cc2234396a8d?auto=format&fit=crop&w=500&q=80",
    "Piña unidad":"https://images.unsplash.com/photo-1550258987-190a2d41a8ba?auto=format&fit=crop&w=500&q=80",
    "Mango kg":"https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=500&q=80",
    "Mandarina kg":"img/mandarina.jpg",
    "Fresa bandeja":"https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=500&q=80",
    "Palta kg":"https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?auto=format&fit=crop&w=500&q=80",

    "Papa Blanca kg":"https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=500&q=80",
    "Cebolla Roja kg":"https://images.unsplash.com/photo-1508747703725-719777637510?auto=format&fit=crop&w=500&q=80",
    "Tomate kg":"https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=500&q=80",
    "Zanahoria kg":"https://images.unsplash.com/photo-1447175008436-054170c2e979?auto=format&fit=crop&w=500&q=80",
    "Lechuga unidad":"https://images.unsplash.com/photo-1622206151226-18ca2c9ab4a1?auto=format&fit=crop&w=500&q=80",
    "Brócoli unidad":"https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?auto=format&fit=crop&w=500&q=80",
    "Pepino kg":"https://images.unsplash.com/photo-1604977042946-1eecc30f269e?auto=format&fit=crop&w=500&q=80",
    "Camote kg":"img/camote.jpg",
    "Choclo unidad":"https://images.unsplash.com/photo-1551754655-cd27e38d2076?auto=format&fit=crop&w=500&q=80",
    "Limón kg":"https://images.unsplash.com/photo-1590502593747-42a996133562?auto=format&fit=crop&w=500&q=80",

    "Pollo kg":"https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80",
    "Carne Molida kg":"https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=500&q=80",
    "Chuleta kg":"https://images.unsplash.com/photo-1602470520998-f4a52199a3d6?auto=format&fit=crop&w=500&q=80",
    "Pescado kg":"https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=500&q=80",
    "Hamburguesa x4":"https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=500&q=80",
    "Hot Dog x6":"https://images.unsplash.com/photo-1612392062631-94dd858cba88?auto=format&fit=crop&w=500&q=80",
    "Jamón 200g":"https://images.unsplash.com/photo-1524438418049-ab2acb7aa48f?auto=format&fit=crop&w=500&q=80",
    "Pechuga kg":"https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=500&q=80",
    "Chorizo x5":"https://images.unsplash.com/photo-1524438418049-ab2acb7aa48f?auto=format&fit=crop&w=500&q=80",
    "Tocino 200g":"https://images.unsplash.com/photo-1524438418049-ab2acb7aa48f?auto=format&fit=crop&w=500&q=80",

    "Pan Francés x10":"https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=500&q=80",
    "Pan Integral":"https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=500&q=80",
    "Queque Vainilla":"https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=500&q=80",
    "Croissant":"https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=500&q=80",
    "Pan Molde":"https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=500&q=80",
    "Empanada Pollo":"https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=500&q=80",
    "Tostadas":"https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=500&q=80",
    "Bizcocho":"https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=500&q=80",
    "Pan Ciabatta":"https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=500&q=80",
    "Muffin Chocolate":"https://images.unsplash.com/photo-1607958996333-41aef7caefaa?auto=format&fit=crop&w=500&q=80"
  };
  return imagenes[p.nombre] || p.imagen;
}
function bannerCategoria(c){return `https://placehold.co/1200x400/0f172a/ffffff?text=${encodeURIComponent(c)}`}
function filtrarCategoria(c){cat.value=c;render();document.querySelectorAll(".cat-btn").forEach(b=>b.classList.remove("active"));[...document.querySelectorAll(".cat-btn")].find(b=>b.textContent.includes(c))?.classList.add("active")}
function render(){let lista=[...productos];if(cat.value!=="Todos")lista=lista.filter(p=>p.categoria===cat.value);if(buscar.value.trim()!=="")lista=lista.filter(p=>p.nombre.toLowerCase().includes(buscar.value.toLowerCase()));if(orden.value==="menor")lista.sort((a,b)=>a.precio-b.precio);if(orden.value==="mayor")lista.sort((a,b)=>b.precio-a.precio);if(orden.value==="az")lista.sort((a,b)=>a.nombre.localeCompare(b.nombre));document.getElementById("resultado").textContent=`Mostrando ${lista.length} productos`;const grupos={};lista.forEach(p=>{if(!grupos[p.categoria])grupos[p.categoria]=[];grupos[p.categoria].push(p)});grid.innerHTML=Object.keys(grupos).map(c=>`<section class="categoria-bloque"><div class="banner-categoria" style="background-image:url('${bannerCategoria(c)}')"><div><h2>${iconoCategoria(c)} ${c}</h2><p>Encuentra los mejores productos de ${c.toLowerCase()}.</p></div></div><div class="categoria-header"><h3>${c}</h3><span>${grupos[c].length} productos</span></div><div class="grid-categoria">${grupos[c].map(p=>`<div class="card"><img src="${imagenProducto(p)}" alt="${p.nombre}"><div class="info"><span class="tag">${p.categoria}</span><h3>${p.nombre}</h3><p class="stock">Stock disponible: ${p.stock}</p><p class="precio">S/ ${Number(p.precio).toFixed(2)}</p><button onclick="agregar(${p.id})" class="btn">Agregar al carrito</button></div></div>`).join("")}</div></section>`).join("")}
function agregar(id){

    console.log(productos);

    const producto = productos.find(p=>Number(p.id)===Number(id));

    console.log(producto);

    const item = carrito.find(p=>Number(p.id)===Number(id));

    item ? item.cant++ : carrito.push({...producto,cant:1});

    guardar();

    toast("Producto agregado al carrito");

}
function guardar(){localStorage.setItem("carrito",JSON.stringify(carrito));pintarCarrito()}
function pintarCarrito(){document.getElementById("contador").textContent=carrito.reduce((a,b)=>a+b.cant,0);document.getElementById("itemsCarrito").innerHTML=carrito.length?carrito.map(i=>`<div class="item"><div><b>${i.nombre}</b><br>S/ ${Number(i.precio).toFixed(2)} x ${i.cant}</div><button onclick="quitar(${i.id})">Quitar</button></div>`).join(""):"<p>Tu carrito está vacío.</p>";document.getElementById("total").textContent=carrito.reduce((a,b)=>a + Number(b.precio) * b.cant, 0).toFixed(2)}
function quitar(id){const item=carrito.find(i=>Number(i.id)===Number(id));if(item){if(item.cant>1){item.cant--;}else{carrito=carrito.filter(i=>Number(i.id)!==Number(id));}guardar();toast("Producto eliminado");}}
function toast(texto){const t=document.getElementById("toast");t.textContent=texto;t.style.display="block";setTimeout(()=>t.style.display="none",1800)}
buscar.addEventListener("input",render);cat.addEventListener("change",render);orden.addEventListener("change",render);
document.getElementById("btnCarrito").onclick=()=>document.getElementById("carrito").classList.add("open");
document.getElementById("cerrarCarrito").onclick=()=>document.getElementById("carrito").classList.remove("open");
document.getElementById("vaciar").onclick=()=>{carrito=[];guardar();toast("Carrito vaciado")};
document.getElementById("comprar").onclick=()=>{if(!carrito.length)return toast("Tu carrito está vacío");fetch("php/ventas/comprar.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({carrito:carrito,total:document.getElementById("total").textContent})}).then(r=>r.json()).then(data=>{if(data.ok){toast("Compra realizada correctamente");carrito=[];guardar();}else{toast(data.mensaje);}}).catch(()=>toast("Error al registrar la compra"))};
document.getElementById("modoOscuro").onclick=()=>document.body.classList.toggle("dark");
document.getElementById("formContacto").onsubmit=e=>{e.preventDefault();fetch("php/contacto/enviar.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({nombre:document.getElementById("nombre").value,correo:document.getElementById("correo").value,asunto:document.getElementById("asunto").value,mensaje:document.getElementById("mensaje").value})}).then(r=>r.json()).then(data=>{if(data.ok){toast("Mensaje enviado correctamente");e.target.reset();}else{toast(data.mensaje);}}).catch(()=>toast("Error al enviar el mensaje"));};
//document.getElementById("ofertasGrid").innerHTML=productos.slice(0,8).map(p=>`<div class="card"><img src="${imagenProducto(p)}" alt="${p.nombre}"><div class="info"><span class="tag">Oferta</span><h3>${p.nombre}</h3><p class="stock">Antes: S/ ${p.precio.toFixed(2)}</p><p class="precio">S/ ${(p.precio*.85).toFixed(2)}</p><button onclick="agregar(${p.id})" class="btn">Agregar oferta</button></div></div>`).join("");
const chatToggle=document.getElementById("chatToggle"),chatBox=document.getElementById("chatBox"),cerrarChat=document.getElementById("cerrarChat"),chatForm=document.getElementById("chatForm"),chatInput=document.getElementById("chatInput"),chatMensajes=document.getElementById("chatMensajes");
chatToggle.onclick=()=>chatBox.classList.toggle("open");cerrarChat.onclick=()=>chatBox.classList.remove("open");
chatForm.onsubmit=e=>{e.preventDefault();const texto=chatInput.value.trim();if(!texto)return;agregarMensaje(texto,"user");responderBot(texto);chatInput.value=""};
function agregarMensaje(texto,tipo){const div=document.createElement("div");div.className=tipo;div.textContent=texto;chatMensajes.appendChild(div);chatMensajes.scrollTop=chatMensajes.scrollHeight}
function responderBot(texto){texto=texto.toLowerCase();let r="Gracias por escribirnos. Puedes consultar por productos, delivery, ofertas, pagos o carrito.";if(texto.includes("producto")||texto.includes("catálogo"))r="Tenemos 100 productos ordenados por categorías.";else if(texto.includes("delivery")||texto.includes("envío"))r="Sí, contamos con delivery rápido según tu zona.";else if(texto.includes("oferta")||texto.includes("descuento"))r="Tenemos ofertas con 15% de descuento en productos seleccionados.";else if(texto.includes("carrito"))r="Agrega productos y finaliza tu compra desde el botón superior.";else if(texto.includes("pago"))r="En el avance final puedes agregar pago con tarjeta o Yape.";setTimeout(()=>agregarMensaje(r,"bot"),500)}
function respuestaRapida(tipo){agregarMensaje(tipo==="productos"?"¿Qué productos tienen?":tipo==="delivery"?"¿Tienen delivery?":"¿Hay ofertas?","user");responderBot(tipo)}
fetch("php/productos/obtener.php")
.then(response => response.json())
.then(data => {

    console.log(data);
    console.log(Array.isArray(data));

    productos = data;

    // Limpiar categorías
    cat.innerHTML = "";
    categoriasMenu.innerHTML = "";

    // Agregar opción "Todos"
    cat.innerHTML += `<option value="Todos">Todos</option>`;

    // Obtener categorías únicas
    const categorias = [...new Set(productos.map(p => p.categoria))];

    categorias.forEach(c => {

        cat.innerHTML += `<option value="${c}">${c}</option>`;

        categoriasMenu.innerHTML += `
        <button class="cat-btn"
            onclick="filtrarCategoria('${c}')">
            ${iconoCategoria(c)} ${c}
        </button>`;

    });

    render();
    pintarCarrito();

    document.getElementById("ofertasGrid").innerHTML =
    productos.slice(0,8).map(p => `
    <div class="card">
        <img src="${imagenProducto(p)}" alt="${p.nombre}">
        <div class="info">
            <span class="tag">Oferta</span>
            <h3>${p.nombre}</h3>
            <p class="stock">Antes: S/ ${Number(p.precio).toFixed(2)}</p>
            <p class="precio">S/ ${(Number(p.precio) * 0.85).toFixed(2)}</p>
            <button onclick="agregar(${p.id})" class="btn">
                Agregar oferta
            </button>
        </div>
    </div>
    `).join("");

})   

.catch(error => {
    console.error(error);
});