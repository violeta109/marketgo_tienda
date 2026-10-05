const productos = [
  {
    "id": 1,
    "nombre": "Arroz Costeño 1kg",
    "categoria": "Abarrotes",
    "precio": 8.5,
    "stock": 71,
    "img": "https://picsum.photos/seed/marketgo1/300/220"
  },
  {
    "id": 2,
    "nombre": "Azúcar Rubia 1kg",
    "categoria": "Abarrotes",
    "precio": 26.4,
    "stock": 72,
    "img": "https://picsum.photos/seed/marketgo2/300/220"
  },
  {
    "id": 3,
    "nombre": "Fideos Spaghetti",
    "categoria": "Abarrotes",
    "precio": 14.4,
    "stock": 43,
    "img": "https://picsum.photos/seed/marketgo3/300/220"
  },
  {
    "id": 4,
    "nombre": "Aceite Vegetal 1L",
    "categoria": "Abarrotes",
    "precio": 28.0,
    "stock": 21,
    "img": "https://picsum.photos/seed/marketgo4/300/220"
  },
  {
    "id": 5,
    "nombre": "Lentejas 500g",
    "categoria": "Abarrotes",
    "precio": 23.7,
    "stock": 43,
    "img": "https://picsum.photos/seed/marketgo5/300/220"
  },
  {
    "id": 6,
    "nombre": "Atún en lata",
    "categoria": "Abarrotes",
    "precio": 19.0,
    "stock": 41,
    "img": "https://picsum.photos/seed/marketgo6/300/220"
  },
  {
    "id": 7,
    "nombre": "Sal de mesa",
    "categoria": "Abarrotes",
    "precio": 11.0,
    "stock": 12,
    "img": "https://picsum.photos/seed/marketgo7/300/220"
  },
  {
    "id": 8,
    "nombre": "Harina preparada",
    "categoria": "Abarrotes",
    "precio": 8.9,
    "stock": 62,
    "img": "https://picsum.photos/seed/marketgo8/300/220"
  },
  {
    "id": 9,
    "nombre": "Avena 500g",
    "categoria": "Abarrotes",
    "precio": 2.4,
    "stock": 54,
    "img": "https://picsum.photos/seed/marketgo9/300/220"
  },
  {
    "id": 10,
    "nombre": "Quinua 500g",
    "categoria": "Abarrotes",
    "precio": 5.9,
    "stock": 74,
    "img": "https://picsum.photos/seed/marketgo10/300/220"
  },
  {
    "id": 11,
    "nombre": "Agua Mineral",
    "categoria": "Bebidas",
    "precio": 16.6,
    "stock": 29,
    "img": "https://picsum.photos/seed/marketgo11/300/220"
  },
  {
    "id": 12,
    "nombre": "Gaseosa Cola",
    "categoria": "Bebidas",
    "precio": 7.7,
    "stock": 64,
    "img": "https://picsum.photos/seed/marketgo12/300/220"
  },
  {
    "id": 13,
    "nombre": "Jugo de Naranja",
    "categoria": "Bebidas",
    "precio": 30.4,
    "stock": 60,
    "img": "https://picsum.photos/seed/marketgo13/300/220"
  },
  {
    "id": 14,
    "nombre": "Energizante",
    "categoria": "Bebidas",
    "precio": 2.6,
    "stock": 72,
    "img": "https://picsum.photos/seed/marketgo14/300/220"
  },
  {
    "id": 15,
    "nombre": "Té Helado",
    "categoria": "Bebidas",
    "precio": 12.0,
    "stock": 48,
    "img": "https://picsum.photos/seed/marketgo15/300/220"
  },
  {
    "id": 16,
    "nombre": "Café Instantáneo",
    "categoria": "Bebidas",
    "precio": 29.5,
    "stock": 13,
    "img": "https://picsum.photos/seed/marketgo16/300/220"
  },
  {
    "id": 17,
    "nombre": "Leche Chocolatada",
    "categoria": "Bebidas",
    "precio": 17.6,
    "stock": 57,
    "img": "https://picsum.photos/seed/marketgo17/300/220"
  },
  {
    "id": 18,
    "nombre": "Bebida Rehidratante",
    "categoria": "Bebidas",
    "precio": 8.8,
    "stock": 10,
    "img": "https://picsum.photos/seed/marketgo18/300/220"
  },
  {
    "id": 19,
    "nombre": "Agua con gas",
    "categoria": "Bebidas",
    "precio": 2.1,
    "stock": 24,
    "img": "https://picsum.photos/seed/marketgo19/300/220"
  },
  {
    "id": 20,
    "nombre": "Refresco de Maracuyá",
    "categoria": "Bebidas",
    "precio": 27.1,
    "stock": 57,
    "img": "https://picsum.photos/seed/marketgo20/300/220"
  },
  {
    "id": 21,
    "nombre": "Detergente 1kg",
    "categoria": "Limpieza",
    "precio": 9.9,
    "stock": 52,
    "img": "https://picsum.photos/seed/marketgo21/300/220"
  },
  {
    "id": 22,
    "nombre": "Lejía 1L",
    "categoria": "Limpieza",
    "precio": 8.9,
    "stock": 55,
    "img": "https://picsum.photos/seed/marketgo22/300/220"
  },
  {
    "id": 23,
    "nombre": "Lavavajilla",
    "categoria": "Limpieza",
    "precio": 6.5,
    "stock": 12,
    "img": "https://picsum.photos/seed/marketgo23/300/220"
  },
  {
    "id": 24,
    "nombre": "Suavizante",
    "categoria": "Limpieza",
    "precio": 1.7,
    "stock": 46,
    "img": "https://picsum.photos/seed/marketgo24/300/220"
  },
  {
    "id": 25,
    "nombre": "Limpiatodo",
    "categoria": "Limpieza",
    "precio": 17.0,
    "stock": 47,
    "img": "https://picsum.photos/seed/marketgo25/300/220"
  },
  {
    "id": 26,
    "nombre": "Desinfectante",
    "categoria": "Limpieza",
    "precio": 26.8,
    "stock": 77,
    "img": "https://picsum.photos/seed/marketgo26/300/220"
  },
  {
    "id": 27,
    "nombre": "Papel Toalla",
    "categoria": "Limpieza",
    "precio": 27.5,
    "stock": 9,
    "img": "https://picsum.photos/seed/marketgo27/300/220"
  },
  {
    "id": 28,
    "nombre": "Esponjas x3",
    "categoria": "Limpieza",
    "precio": 21.8,
    "stock": 80,
    "img": "https://picsum.photos/seed/marketgo28/300/220"
  },
  {
    "id": 29,
    "nombre": "Bolsa Basura",
    "categoria": "Limpieza",
    "precio": 34.0,
    "stock": 24,
    "img": "https://picsum.photos/seed/marketgo29/300/220"
  },
  {
    "id": 30,
    "nombre": "Ambientador",
    "categoria": "Limpieza",
    "precio": 15.9,
    "stock": 18,
    "img": "https://picsum.photos/seed/marketgo30/300/220"
  },
  {
    "id": 31,
    "nombre": "Shampoo",
    "categoria": "Cuidado personal",
    "precio": 34.6,
    "stock": 47,
    "img": "https://picsum.photos/seed/marketgo31/300/220"
  },
  {
    "id": 32,
    "nombre": "Jabón Tocador",
    "categoria": "Cuidado personal",
    "precio": 4.0,
    "stock": 47,
    "img": "https://picsum.photos/seed/marketgo32/300/220"
  },
  {
    "id": 33,
    "nombre": "Pasta Dental",
    "categoria": "Cuidado personal",
    "precio": 15.3,
    "stock": 75,
    "img": "https://picsum.photos/seed/marketgo33/300/220"
  },
  {
    "id": 34,
    "nombre": "Cepillo Dental",
    "categoria": "Cuidado personal",
    "precio": 16.4,
    "stock": 21,
    "img": "https://picsum.photos/seed/marketgo34/300/220"
  },
  {
    "id": 35,
    "nombre": "Desodorante",
    "categoria": "Cuidado personal",
    "precio": 11.4,
    "stock": 21,
    "img": "https://picsum.photos/seed/marketgo35/300/220"
  },
  {
    "id": 36,
    "nombre": "Papel Higiénico",
    "categoria": "Cuidado personal",
    "precio": 26.6,
    "stock": 30,
    "img": "https://picsum.photos/seed/marketgo36/300/220"
  },
  {
    "id": 37,
    "nombre": "Crema Corporal",
    "categoria": "Cuidado personal",
    "precio": 24.5,
    "stock": 80,
    "img": "https://picsum.photos/seed/marketgo37/300/220"
  },
  {
    "id": 38,
    "nombre": "Gel antibacterial",
    "categoria": "Cuidado personal",
    "precio": 3.9,
    "stock": 65,
    "img": "https://picsum.photos/seed/marketgo38/300/220"
  },
  {
    "id": 39,
    "nombre": "Acondicionador",
    "categoria": "Cuidado personal",
    "precio": 13.1,
    "stock": 59,
    "img": "https://picsum.photos/seed/marketgo39/300/220"
  },
  {
    "id": 40,
    "nombre": "Talco",
    "categoria": "Cuidado personal",
    "precio": 14.9,
    "stock": 18,
    "img": "https://picsum.photos/seed/marketgo40/300/220"
  },
  {
    "id": 41,
    "nombre": "Papas Fritas",
    "categoria": "Snacks",
    "precio": 24.1,
    "stock": 58,
    "img": "https://picsum.photos/seed/marketgo41/300/220"
  },
  {
    "id": 42,
    "nombre": "Galletas Chocolate",
    "categoria": "Snacks",
    "precio": 14.2,
    "stock": 32,
    "img": "https://picsum.photos/seed/marketgo42/300/220"
  },
  {
    "id": 43,
    "nombre": "Chocolate Barra",
    "categoria": "Snacks",
    "precio": 13.4,
    "stock": 9,
    "img": "https://picsum.photos/seed/marketgo43/300/220"
  },
  {
    "id": 44,
    "nombre": "Maní Salado",
    "categoria": "Snacks",
    "precio": 7.0,
    "stock": 66,
    "img": "https://picsum.photos/seed/marketgo44/300/220"
  },
  {
    "id": 45,
    "nombre": "Cereal Bar",
    "categoria": "Snacks",
    "precio": 10.2,
    "stock": 56,
    "img": "https://picsum.photos/seed/marketgo45/300/220"
  },
  {
    "id": 46,
    "nombre": "Chifles",
    "categoria": "Snacks",
    "precio": 14.4,
    "stock": 36,
    "img": "https://picsum.photos/seed/marketgo46/300/220"
  },
  {
    "id": 47,
    "nombre": "Gomitas",
    "categoria": "Snacks",
    "precio": 20.7,
    "stock": 27,
    "img": "https://picsum.photos/seed/marketgo47/300/220"
  },
  {
    "id": 48,
    "nombre": "Nachos",
    "categoria": "Snacks",
    "precio": 9.2,
    "stock": 27,
    "img": "https://picsum.photos/seed/marketgo48/300/220"
  },
  {
    "id": 49,
    "nombre": "Pop Corn",
    "categoria": "Snacks",
    "precio": 24.0,
    "stock": 53,
    "img": "https://picsum.photos/seed/marketgo49/300/220"
  },
  {
    "id": 50,
    "nombre": "Galleta Soda",
    "categoria": "Snacks",
    "precio": 26.6,
    "stock": 76,
    "img": "https://picsum.photos/seed/marketgo50/300/220"
  },
  {
    "id": 51,
    "nombre": "Leche Evaporada",
    "categoria": "Lácteos",
    "precio": 15.9,
    "stock": 40,
    "img": "https://picsum.photos/seed/marketgo51/300/220"
  },
  {
    "id": 52,
    "nombre": "Yogurt Fresa",
    "categoria": "Lácteos",
    "precio": 17.8,
    "stock": 11,
    "img": "https://picsum.photos/seed/marketgo52/300/220"
  },
  {
    "id": 53,
    "nombre": "Queso Fresco",
    "categoria": "Lácteos",
    "precio": 31.1,
    "stock": 36,
    "img": "https://picsum.photos/seed/marketgo53/300/220"
  },
  {
    "id": 54,
    "nombre": "Mantequilla",
    "categoria": "Lácteos",
    "precio": 34.3,
    "stock": 62,
    "img": "https://picsum.photos/seed/marketgo54/300/220"
  },
  {
    "id": 55,
    "nombre": "Leche UHT",
    "categoria": "Lácteos",
    "precio": 32.5,
    "stock": 32,
    "img": "https://picsum.photos/seed/marketgo55/300/220"
  },
  {
    "id": 56,
    "nombre": "Yogurt Natural",
    "categoria": "Lácteos",
    "precio": 21.7,
    "stock": 22,
    "img": "https://picsum.photos/seed/marketgo56/300/220"
  },
  {
    "id": 57,
    "nombre": "Queso Edam",
    "categoria": "Lácteos",
    "precio": 10.6,
    "stock": 29,
    "img": "https://picsum.photos/seed/marketgo57/300/220"
  },
  {
    "id": 58,
    "nombre": "Crema de Leche",
    "categoria": "Lácteos",
    "precio": 7.8,
    "stock": 76,
    "img": "https://picsum.photos/seed/marketgo58/300/220"
  },
  {
    "id": 59,
    "nombre": "Manjar Blanco",
    "categoria": "Lácteos",
    "precio": 33.4,
    "stock": 48,
    "img": "https://picsum.photos/seed/marketgo59/300/220"
  },
  {
    "id": 60,
    "nombre": "Helado Vainilla",
    "categoria": "Lácteos",
    "precio": 25.9,
    "stock": 74,
    "img": "https://picsum.photos/seed/marketgo60/300/220"
  },
  {
    "id": 61,
    "nombre": "Manzana Roja kg",
    "categoria": "Frutas",
    "precio": 2.4,
    "stock": 64,
    "img": "https://picsum.photos/seed/marketgo61/300/220"
  },
  {
    "id": 62,
    "nombre": "Plátano kg",
    "categoria": "Frutas",
    "precio": 17.8,
    "stock": 21,
    "img": "https://picsum.photos/seed/marketgo62/300/220"
  },
  {
    "id": 63,
    "nombre": "Naranja kg",
    "categoria": "Frutas",
    "precio": 28.3,
    "stock": 62,
    "img": "https://picsum.photos/seed/marketgo63/300/220"
  },
  {
    "id": 64,
    "nombre": "Uva kg",
    "categoria": "Frutas",
    "precio": 26.9,
    "stock": 19,
    "img": "https://picsum.photos/seed/marketgo64/300/220"
  },
  {
    "id": 65,
    "nombre": "Papaya kg",
    "categoria": "Frutas",
    "precio": 9.0,
    "stock": 59,
    "img": "https://picsum.photos/seed/marketgo65/300/220"
  },
  {
    "id": 66,
    "nombre": "Piña unidad",
    "categoria": "Frutas",
    "precio": 6.1,
    "stock": 58,
    "img": "https://picsum.photos/seed/marketgo66/300/220"
  },
  {
    "id": 67,
    "nombre": "Mango kg",
    "categoria": "Frutas",
    "precio": 26.8,
    "stock": 43,
    "img": "https://picsum.photos/seed/marketgo67/300/220"
  },
  {
    "id": 68,
    "nombre": "Mandarina kg",
    "categoria": "Frutas",
    "precio": 18.3,
    "stock": 52,
    "img": "https://picsum.photos/seed/marketgo68/300/220"
  },
  {
    "id": 69,
    "nombre": "Fresa bandeja",
    "categoria": "Frutas",
    "precio": 21.6,
    "stock": 22,
    "img": "https://picsum.photos/seed/marketgo69/300/220"
  },
  {
    "id": 70,
    "nombre": "Palta kg",
    "categoria": "Frutas",
    "precio": 2.9,
    "stock": 75,
    "img": "https://picsum.photos/seed/marketgo70/300/220"
  },
  {
    "id": 71,
    "nombre": "Papa Blanca kg",
    "categoria": "Verduras",
    "precio": 7.4,
    "stock": 64,
    "img": "https://picsum.photos/seed/marketgo71/300/220"
  },
  {
    "id": 72,
    "nombre": "Cebolla Roja kg",
    "categoria": "Verduras",
    "precio": 30.6,
    "stock": 73,
    "img": "https://picsum.photos/seed/marketgo72/300/220"
  },
  {
    "id": 73,
    "nombre": "Tomate kg",
    "categoria": "Verduras",
    "precio": 18.6,
    "stock": 39,
    "img": "https://picsum.photos/seed/marketgo73/300/220"
  },
  {
    "id": 74,
    "nombre": "Zanahoria kg",
    "categoria": "Verduras",
    "precio": 17.1,
    "stock": 68,
    "img": "https://picsum.photos/seed/marketgo74/300/220"
  },
  {
    "id": 75,
    "nombre": "Lechuga unidad",
    "categoria": "Verduras",
    "precio": 7.8,
    "stock": 63,
    "img": "https://picsum.photos/seed/marketgo75/300/220"
  },
  {
    "id": 76,
    "nombre": "Brócoli unidad",
    "categoria": "Verduras",
    "precio": 26.2,
    "stock": 73,
    "img": "https://picsum.photos/seed/marketgo76/300/220"
  },
  {
    "id": 77,
    "nombre": "Pepino kg",
    "categoria": "Verduras",
    "precio": 34.5,
    "stock": 61,
    "img": "https://picsum.photos/seed/marketgo77/300/220"
  },
  {
    "id": 78,
    "nombre": "Camote kg",
    "categoria": "Verduras",
    "precio": 28.0,
    "stock": 9,
    "img": "https://picsum.photos/seed/marketgo78/300/220"
  },
  {
    "id": 79,
    "nombre": "Choclo unidad",
    "categoria": "Verduras",
    "precio": 23.9,
    "stock": 49,
    "img": "https://picsum.photos/seed/marketgo79/300/220"
  },
  {
    "id": 80,
    "nombre": "Limón kg",
    "categoria": "Verduras",
    "precio": 1.8,
    "stock": 51,
    "img": "https://picsum.photos/seed/marketgo80/300/220"
  },
  {
    "id": 81,
    "nombre": "Pollo kg",
    "categoria": "Carnes",
    "precio": 6.5,
    "stock": 77,
    "img": "https://picsum.photos/seed/marketgo81/300/220"
  },
  {
    "id": 82,
    "nombre": "Carne Molida kg",
    "categoria": "Carnes",
    "precio": 3.3,
    "stock": 79,
    "img": "https://picsum.photos/seed/marketgo82/300/220"
  },
  {
    "id": 83,
    "nombre": "Chuleta kg",
    "categoria": "Carnes",
    "precio": 15.7,
    "stock": 72,
    "img": "https://picsum.photos/seed/marketgo83/300/220"
  },
  {
    "id": 84,
    "nombre": "Pescado kg",
    "categoria": "Carnes",
    "precio": 17.0,
    "stock": 70,
    "img": "https://picsum.photos/seed/marketgo84/300/220"
  },
  {
    "id": 85,
    "nombre": "Hamburguesa x4",
    "categoria": "Carnes",
    "precio": 25.4,
    "stock": 68,
    "img": "https://picsum.photos/seed/marketgo85/300/220"
  },
  {
    "id": 86,
    "nombre": "Hot Dog x6",
    "categoria": "Carnes",
    "precio": 21.0,
    "stock": 66,
    "img": "https://picsum.photos/seed/marketgo86/300/220"
  },
  {
    "id": 87,
    "nombre": "Jamón 200g",
    "categoria": "Carnes",
    "precio": 9.8,
    "stock": 8,
    "img": "https://picsum.photos/seed/marketgo87/300/220"
  },
  {
    "id": 88,
    "nombre": "Pechuga kg",
    "categoria": "Carnes",
    "precio": 15.3,
    "stock": 57,
    "img": "https://picsum.photos/seed/marketgo88/300/220"
  },
  {
    "id": 89,
    "nombre": "Chorizo x5",
    "categoria": "Carnes",
    "precio": 23.5,
    "stock": 33,
    "img": "https://picsum.photos/seed/marketgo89/300/220"
  },
  {
    "id": 90,
    "nombre": "Tocino 200g",
    "categoria": "Carnes",
    "precio": 32.3,
    "stock": 30,
    "img": "https://picsum.photos/seed/marketgo90/300/220"
  },
  {
    "id": 91,
    "nombre": "Pan Francés x10",
    "categoria": "Panadería",
    "precio": 17.5,
    "stock": 31,
    "img": "https://picsum.photos/seed/marketgo91/300/220"
  },
  {
    "id": 92,
    "nombre": "Pan Integral",
    "categoria": "Panadería",
    "precio": 25.5,
    "stock": 48,
    "img": "https://picsum.photos/seed/marketgo92/300/220"
  },
  {
    "id": 93,
    "nombre": "Queque Vainilla",
    "categoria": "Panadería",
    "precio": 7.6,
    "stock": 48,
    "img": "https://picsum.photos/seed/marketgo93/300/220"
  },
  {
    "id": 94,
    "nombre": "Croissant",
    "categoria": "Panadería",
    "precio": 23.1,
    "stock": 15,
    "img": "https://picsum.photos/seed/marketgo94/300/220"
  },
  {
    "id": 95,
    "nombre": "Pan Molde",
    "categoria": "Panadería",
    "precio": 20.6,
    "stock": 33,
    "img": "https://picsum.photos/seed/marketgo95/300/220"
  },
  {
    "id": 96,
    "nombre": "Empanada Pollo",
    "categoria": "Panadería",
    "precio": 3.8,
    "stock": 27,
    "img": "https://picsum.photos/seed/marketgo96/300/220"
  },
  {
    "id": 97,
    "nombre": "Tostadas",
    "categoria": "Panadería",
    "precio": 11.0,
    "stock": 75,
    "img": "https://picsum.photos/seed/marketgo97/300/220"
  },
  {
    "id": 98,
    "nombre": "Bizcocho",
    "categoria": "Panadería",
    "precio": 20.5,
    "stock": 79,
    "img": "https://picsum.photos/seed/marketgo98/300/220"
  },
  {
    "id": 99,
    "nombre": "Pan Ciabatta",
    "categoria": "Panadería",
    "precio": 4.3,
    "stock": 16,
    "img": "https://picsum.photos/seed/marketgo99/300/220"
  },
  {
    "id": 100,
    "nombre": "Muffin Chocolate",
    "categoria": "Panadería",
    "precio": 14.3,
    "stock": 17,
    "img": "https://picsum.photos/seed/marketgo100/300/220"
  }
];

// Vista previa de fotografía al registrar un producto.
document.addEventListener('change', function (event) {
  const input = event.target.closest('[data-image-preview]');
  if (!input) return;
  const preview = document.getElementById(input.dataset.imagePreview);
  if (!preview) return;
  const file = input.files && input.files[0];
  if (!file) { preview.removeAttribute('src'); preview.hidden = true; return; }
  if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 5 * 1024 * 1024) {
    input.value = ''; preview.removeAttribute('src'); preview.hidden = true;
    alert('Selecciona una imagen JPG, PNG o WEBP de máximo 5 MB.'); return;
  }
  preview.src = URL.createObjectURL(file); preview.hidden = false;
});
