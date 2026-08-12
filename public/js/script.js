/* 
 Script de SmartBudget - Ángel Felipe Rodríguez Vargas
 */

/*  ===================================================
 *      Html de configuración
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", () => {

        const formPerfil = document.getElementById("form-perfil");
        const formNotificaciones = document.getElementById("form-notificaciones");

        // Validación del perfil
        formPerfil.addEventListener("submit", function (e) {

            const nombre = document.getElementById("nombre-perfil").value.trim();
            const email = document.getElementById("email-perfil").value.trim();

            if (nombre === "" || email === "") {
                e.preventDefault();
                alert("Debe completar todos los datos.");
            } else {
                alert("Datos enviados correctamente.");
            }

        });

        // Confirmación de notificaciones
        formNotificaciones.addEventListener("submit", function () {
            alert("Preferencias de notificación guardadas.");
        });

    });

/*  ===================================================
 *      Html del dashboard
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {
        // Gráfico de gastos por categoría
        const graficoCategorias = document.getElementById("grafico-categorias");
        const gastosCategoriaData = document.getElementById("gastos-categoria-data");
        const gastosPorCategoriaArregloJavaScript = gastosCategoriaData
            ? JSON.parse(gastosCategoriaData.textContent)
            : [];
        const categoriasGasto = gastosPorCategoriaArregloJavaScript.map(dato => dato.categoria);
        const montosGasto = gastosPorCategoriaArregloJavaScript.map(dato => dato.total);
        const coloresCategorias = [
            "#3fb68b",
            "#f39640",
            "#0e4f4a",
            "#ef476f",
            "#8ecae6",
            "#ffb703",
            "#7b2cbf",
            "#2a9d8f",
            "#f94144",
            "#94d2bd"
        ];
        const coloresPorCategoria = categoriasGasto.map((_, indice) => coloresCategorias[indice % coloresCategorias.length]);

        // Gráfico de ingresos y gastos últimos 6 meses
        const graficoSeisMeses = document.getElementById("grafico-seis-meses");

        //Gráfico circular de la meta 1
        const graficoMeta1 = document.getElementById("grafico-meta-1");
        //Gráfico circular de la meta 2
        const graficoMeta2 = document.getElementById("grafico-meta-2");
        //Gráfico circular de la meta 3
        const graficoMeta3 = document.getElementById("grafico-meta-3");
        //Gráfico circular de la meta 4
        const graficoMeta4 = document.getElementById("grafico-meta-4");


        //Datos de ingresos
        const ingresosSeisMesesData = document.getElementById("ingresos-seis-meses-data");//Obtiene el elemento del DOM que contiene los datos en formato JSON
        const ingresoSeisMesesArregloJavaScript = ingresosSeisMesesData//Convierte los datos JSON en un arreglo de JavaScript
            ? JSON.parse(ingresosSeisMesesData.textContent)
            : [];
        const mesesIngreso = ingresoSeisMesesArregloJavaScript.map(dato => dato.mes);
        const montosIngresoSeisMeses = ingresoSeisMesesArregloJavaScript.map(dato => dato.total);

        //Datos de gastos
        const gastosSeisMesesData = document.getElementById("gastos-seis-meses-data");//Obtiene el elemento del DOM que contiene los datos en formato JSON
        const gastosSeisMesesArregloJavaScript = gastosSeisMesesData//Convierte los datos JSON en un arreglo de JavaScript
            ? JSON.parse(gastosSeisMesesData.textContent)
            : [];
        const mesesGasto = gastosSeisMesesArregloJavaScript.map(dato => dato.mes);
        const montosGastoSeisMeses = gastosSeisMesesArregloJavaScript.map(dato => dato.total);

        //Datos de metas durante los últimos 6 meses
        const metasSeisMesesData = document.getElementById("metas-seis-meses-data");//Obtiene el elemento del DOM que contiene los datos en formato JSON
        const metasSeisMesesArregloJavaScript = metasSeisMesesData//Convierte los datos JSON en un arreglo de JavaScript
            ? JSON.parse(metasSeisMesesData.textContent)
            : [];
        const mesesMetas = metasSeisMesesArregloJavaScript.map(dato => dato.mes);
        const montosMetasSeisMeses = metasSeisMesesArregloJavaScript.map(dato => dato.total);
        
        //Calculo del dinero comprometido (Gastos + Metas) para cada mes
        const montosDineroComprometido = montosGastoSeisMeses.map((gasto, indice) => gasto + (montosMetasSeisMeses[indice] || 0));

        //Datos de las primeras 4 metas registradas.
        const metasCircularesData = document.getElementById("metas-circulares-data");
        const metasCircularesArregloJavaScript = metasCircularesData//Convierte los datos JSON en un arreglo de JavaScript
            ? JSON.parse(metasCircularesData.textContent)
            : [];

        const meta1 = metasCircularesArregloJavaScript[0] ?? null;
        const meta2 = metasCircularesArregloJavaScript[1] ?? null;
        const meta3 = metasCircularesArregloJavaScript[2] ?? null;
        const meta4 = metasCircularesArregloJavaScript[3] ?? null;

        const porcentajeMeta1 = Number(meta1?.porcentaje ?? 0);
        const porcentajeMeta2 = Number(meta2?.porcentaje ?? 0);
        const porcentajeMeta3 = Number(meta3?.porcentaje ?? 0);
        const porcentajeMeta4 = Number(meta4?.porcentaje ?? 0);

        const porcentajePendienteMeta1 = 100 - porcentajeMeta1;
        const porcentajePendienteMeta2 = 100 - porcentajeMeta2;
        const porcentajePendienteMeta3 = 100 - porcentajeMeta3;
        const porcentajePendienteMeta4 = 100 - porcentajeMeta4;


        new Chart(graficoCategorias, {
            type: "bar",

            data: {
                labels: categoriasGasto,

                datasets: [{
                    label: "Monto gastado",

                    data: montosGasto,

                    backgroundColor: coloresPorCategoria,
                    borderColor: "#ffffff",
                    borderWidth: 1,
                    borderRadius: 0
                }]
            },

            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return "₡" +
                                    context.raw.toLocaleString("es-CR");
                            }
                        }
                    }
                },

                scales: {
                    x: {
                        beginAtZero: true,

                        ticks: {
                            callback: function (valor) {
                                return "₡" +
                                    valor.toLocaleString("es-CR");
                            }
                        }
                    }
                }
            }
        });
        new Chart(graficoSeisMeses, {
            type: "line",

            data: {
                labels: mesesIngreso,
                datasets: [{
                    label: "Ingresos",
                    data: montosIngresoSeisMeses,
                    borderColor: "#10b981",
                    backgroundColor: "#10b981",
                    fill: false
                }, {
                    label: "Gastos",
                    data: montosGastoSeisMeses,
                    borderColor: "#ef4444",
                    backgroundColor: "#ef4444",
                    fill: false
                }, {
                    label: "Metas",
                    data: montosMetasSeisMeses,
                    borderColor: "#8b5cf6",
                    backgroundColor: "#8b5cf6",
                    fill: false
                }, {
                    label: "Dinero comprometido (Gastos + Metas)",
                    data: montosDineroComprometido,
                    borderColor: "#f59e0b",
                    backgroundColor: "#f59e0b",
                    fill: false
                }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: true,
                        position: "top"
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label +
                                    ": ₡" +
                                    context.raw.toLocaleString("es-CR");
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            callback: function (valor) {
                                return "₡" +
                                    valor.toLocaleString("es-CR");
                            }
                        }
                    }
                }
            }
        });

        const porcentajeCentro = {
            id: "porcentajeCentro",

            afterDatasetsDraw(chart) {
                const porcentaje = Number(
                    chart.data.datasets[0].data[0]
                );

                const centro = chart.getDatasetMeta(0).data[0];
                const ctx = chart.ctx;

                ctx.save();
                ctx.font = "bold 16px Arial";
                ctx.fillStyle = "#0e4f4a";
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";

                ctx.fillText(
                    porcentaje.toFixed(1) + "%",
                    centro.x,
                    centro.y
                );

                ctx.restore();
            }
        };

        new Chart(graficoMeta1, {
            type: "doughnut",
            data: {
                labels: ["Completado", "Pendiente"],
                datasets: [{
                    data: [
                        porcentajeMeta1, porcentajePendienteMeta1
                    ],

                    backgroundColor: meta1
                    ? ["#10b981", "#d1d5db"]
                    : ["#e5e7eb", "#e5e7eb"],

                    borderWidth: 0 
                }]
            },

            plugins: [porcentajeCentro],

            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: "60%",

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: Boolean(meta1),


                        callbacks: {
                            label: function (context) {
                                return context.label + ": " +
                                    Number(context.raw).toFixed(1) + "%";
                            },

                            afterLabel: function () {
                                return [
                                    "Monto actual: ₡" +
                                        Number(meta1.monto_actual)
                                            .toLocaleString("es-CR"),

                                    "Monto objetivo: ₡" +
                                        Number(meta1.monto_objetivo)
                                            .toLocaleString("es-CR")
                                ];
                            }
                        }
                    }
                }

            }
        });

        new Chart(graficoMeta2, {
            type: "doughnut",
            data: {
                labels: ["Completado", "Pendiente"],
                datasets: [{
                    data: [
                        porcentajeMeta2, porcentajePendienteMeta2
                    ],

                    backgroundColor: meta2
                        ? ["#3b82f6", "#d1d5db"]
                        : ["#e5e7eb", "#e5e7eb"],

                    borderWidth: 0 
                }]
            },

            plugins: [porcentajeCentro],

            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: "60%",

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: Boolean(meta2),


                        callbacks: {
                            label: function (context) {
                                return context.label + ": " +
                                    Number(context.raw).toFixed(1) + "%";
                            },

                            afterLabel: function () {
                                return [
                                    "Monto actual: ₡" +
                                        Number(meta2.monto_actual)
                                            .toLocaleString("es-CR"),

                                    "Monto objetivo: ₡" +
                                        Number(meta2.monto_objetivo)
                                            .toLocaleString("es-CR")
                                ];
                            }
                        }
                    }
                }

            }
        });

        new Chart(graficoMeta3, {
            type: "doughnut",
            data: {
                labels: ["Completado", "Pendiente"],
                datasets: [{
                    data: [
                        porcentajeMeta3, porcentajePendienteMeta3
                    ],

                    backgroundColor: meta3
                        ? ["#8b5cf6", "#d1d5db"]
                        : ["#e5e7eb", "#e5e7eb"],

                    borderWidth: 0 
                }]
            },

            plugins: [porcentajeCentro],

            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: "60%",

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: Boolean(meta3),


                        callbacks: {
                            label: function (context) {
                                return context.label + ": " +
                                    Number(context.raw).toFixed(1) + "%";
                            },

                            afterLabel: function () {
                                return [
                                    "Monto actual: ₡" +
                                        Number(meta3.monto_actual)
                                            .toLocaleString("es-CR"),

                                    "Monto objetivo: ₡" +
                                        Number(meta3.monto_objetivo)
                                            .toLocaleString("es-CR")
                                ];
                            }
                        }
                    }
                }

            }
        });

        new Chart(graficoMeta4, {
            type: "doughnut",
            data: {
                labels: ["Completado", "Pendiente"],
                datasets: [{
                    data: [
                        porcentajeMeta4, porcentajePendienteMeta4
                    ],

                    backgroundColor: meta4
                        ? ["#f59e0b", "#d1d5db"]
                        : ["#e5e7eb", "#e5e7eb"],

                    borderWidth: 0 
                }]
            },

            plugins: [porcentajeCentro],

            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: "60%",

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: Boolean(meta4),


                        callbacks: {
                            label: function (context) {
                                return context.label + ": " +
                                    Number(context.raw).toFixed(1) + "%";
                            },

                            afterLabel: function () {
                                return [
                                    "Monto actual: ₡" +
                                        Number(meta4.monto_actual)
                                            .toLocaleString("es-CR"),

                                    "Monto objetivo: ₡" +
                                        Number(meta4.monto_objetivo)
                                            .toLocaleString("es-CR")
                                ];
                            }
                        }
                    }
                }

            }
        });
    });
    


    window.addEventListener('load', function () {
        const toast = document.getElementById('loginToast');

        if (toast) {
            toast.classList.add('show');
        }

        setTimeout(function () {
            toast.classList.remove('show');
        }, 3000);


    });

 /*  ===================================================
 *      Html de gastos
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("form-gasto");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            const nombre = document.getElementById("nombre").value.trim();
            const monto = document.getElementById("monto").value;
            const idCategoria = document.getElementById("id_categoria").value;
            const fecha = document.getElementById("fecha").value;

            if (
                nombre === "" ||
                monto === "" ||
                Number(monto) <= 0 ||
                idCategoria === "" ||
                fecha === ""
            ) {
                e.preventDefault();
                alert("Complete todos los campos obligatorios.");
                return;
            }

            alert("Gasto registrado correctamente");
        });
    });
    
/*  ===================================================
 *      Html de index
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("form-login");

        if (!form) return; // evita que afecte otras páginas

        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const mensajeError = document.getElementById("mensaje-error");

        form.addEventListener("submit", function (e) {

            const emailValue = email.value.trim();
            const passwordValue = password.value.trim();

            // Reset error
            mensajeError.hidden = true;

            // Validaciones básicas
            if (emailValue === "" || passwordValue === "") {
                e.preventDefault();
                mensajeError.textContent = "Por favor complete todos los campos.";
                mensajeError.hidden = false;
                return;
            }

            // Validación de formato de correo
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(emailValue)) {
                e.preventDefault();
                mensajeError.textContent = "Ingrese un correo válido.";
                mensajeError.hidden = false;
                return;
            }
        });
    });
    
/*  ===================================================
 *      Html de ingresos
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("form-ingreso");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            const nombre = document.getElementById("nombre").value.trim();
            const monto = document.getElementById("monto").value;
            const categoriaSelect = document.getElementById("id_categoria");
            const idCategoria = categoriaSelect.value;
            const fecha = document.getElementById("fecha-ingreso").value;

            // Validaciones
            if (
                nombre === "" ||
                monto === "" ||
                Number(monto) <= 0 ||
                idCategoria === "" ||
                fecha === ""
            ) {
                alert("Por favor complete todos los campos obligatorios.");
                e.preventDefault();
                return;
            }

            alert("Ingreso registrado correctamente");
        });
    });

/*  ===================================================
 *      Html de metas
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("form-meta");

        if (!form) return;

        form.addEventListener("submit", function (e) {
            const nombre = document.getElementById("nombre").value.trim();
            const montoInicial = document.getElementById("monto_inicial").value;
            const objetivo = document.getElementById("monto_objetivo").value;
            const cuota = document.getElementById("cuota").value;
            const fechaInicio = document.getElementById("fecha_inicio").value;
            const fechaCumplimiento = document.getElementById("fecha_cumplimiento").value;

            if (
                nombre === "" ||
                montoInicial === "" ||
                objetivo === "" ||
                Number(objetivo) <= 0 ||
                cuota === "" ||
                Number(cuota) <= 0 ||
                fechaInicio === "" ||
                fechaCumplimiento === ""
            ) {
                alert("Por favor complete todos los campos correctamente.");
                e.preventDefault();
                return;
            }
            alert("Meta de ahorro creada ");
        });
    });
    
/*  ===================================================
 *      Html de recuperar
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const formRecuperar = document.getElementById("form-recuperar");
        if (!formRecuperar) return;

        const email = document.getElementById("email");
        const mensajeExito = document.getElementById("mensaje-exito");

        formRecuperar.addEventListener("submit", function (e) {
            e.preventDefault();
            const correo = email.value.trim();

            // Ocultar mensaje anterior
            mensajeExito.hidden = true;

            // Validar correo vacío
            if (correo === "") {
                alert("Ingrese su correo electrónico.");
                return;
            }

            // Validar formato del correo
            const formatoCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!formatoCorreo.test(correo)) {
                alert("Ingrese un correo electrónico válido.");
                return;

            }
            // Simulación de envío
            mensajeExito.textContent =
                "Si el correo existe, se envió un enlace de recuperación.";
            mensajeExito.hidden = false;
            alert("Solicitud enviada correctamente");

            // Limpiar formulario
            formRecuperar.reset();
        });
    });
    
/*  ===================================================
 *      Html de registro
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const formRegistro = document.getElementById("form-registro");
        if (!formRegistro) return;

        const usuario = document.getElementById("usuario");
        const nombre = document.getElementById("nombre");
        const apellido = document.getElementById("apellido");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const password2 = document.getElementById("password2");
        const mensajeError = document.getElementById("mensaje-error");

        formRegistro.addEventListener("submit", function (e) {
            e.preventDefault();

            // Ocultar mensajes anteriores
            mensajeError.hidden = true;

            // Obtener valores
            const usuarioValue = usuario.value.trim();
            const nombreValue = nombre.value.trim();
            const apellidoValue = apellido.value.trim();
            const emailValue = email.value.trim();
            const passwordValue = password.value.trim();
            const password2Value = password2.value.trim();

            // Validar campos vacíos
            if (
                usuarioValue === "" ||
                nombreValue === "" ||
                apellidoValue === "" ||
                emailValue === "" ||
                passwordValue === "" ||
                password2Value === ""
            ) {
                mensajeError.textContent =
                    "Debe completar todos los campos.";

                mensajeError.hidden = false;
                return;
            }

            // Validar correo
            const formatoCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!formatoCorreo.test(emailValue)) {

                mensajeError.textContent =
                    "Ingrese un correo electrónico válido.";

                mensajeError.hidden = false;
                return;
            }

            // Validar longitud contraseña
            if (passwordValue.length < 8) {

                mensajeError.textContent =
                    "La contraseña debe tener mínimo 8 caracteres.";

                mensajeError.hidden = false;
                return;
            }

            // Validar coincidencia
            if (passwordValue !== password2Value) {

                mensajeError.textContent =
                    "Las contraseñas no coinciden.";

                mensajeError.hidden = false;
                return;
            }

            // Simulación de registro exitoso
            alert("Usuario registrado correctamente");

            // Ir al login
            window.location.href = "index.html";

        });
    });
    
/*  ===================================================
 *      Html de reportes
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const formReporte = document.getElementById("form-reporte");
        if (!formReporte) return;

        const periodo = document.getElementById("periodo-reporte");
        const categoria = document.getElementById("categoria-reporte");
        const tbody = document.querySelector("#tabla-reporte tbody");

        const graficoDistribucion = document.getElementById("grafico-distribucion");
        const graficoIngresos = document.getElementById("grafico-ingresos");
        const graficoBalance = document.getElementById("grafico-balance");

        const btnDescargar = document.getElementById("btn-descargar");

        // Datos simulados
        const datos = [
            {
                fecha: "2026-07-01",
                tipo: "Gasto",
                categoria: "alimentacion",
                descripcion: "Supermercado",
                monto: 45000
            },
            {
                fecha: "2026-07-05",
                tipo: "Gasto",
                categoria: "transporte",
                descripcion: "Gasolina",
                monto: 25000
            },
            {
                fecha: "2026-07-10",
                tipo: "Ingreso",
                categoria: "salario",
                descripcion: "Pago mensual",
                monto: 350000
            }
        ];

        formReporte.addEventListener("submit", function (e) {
            // Los filtros se procesan en PHP con los datos reales de la base de datos.
            return;
            const categoriaSeleccionada = categoria.value;
            tbody.innerHTML = "";
            let resultados = datos.filter(function (dato) {
                if (categoriaSeleccionada === "todas") {
                    return true;
                }
                return dato.categoria === categoriaSeleccionada;
            });

            if (resultados.length === 0) {

                tbody.innerHTML = `
                    <tr class="fila-vacia">
                        <td colspan="5">
                            No hay datos para este filtro.
                        </td>
                    </tr>
                `;
                return;
            }

            resultados.forEach(function (dato) {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${dato.fecha}</td>
                    <td>${dato.tipo}</td>
                    <td>${dato.categoria}</td>
                    <td>${dato.descripcion}</td>
                    <td>₡${dato.monto.toLocaleString("es-CR")}</td>
                `;
                tbody.appendChild(fila);
            });
            actualizarGraficos(resultados);
        });

        function actualizarGraficos(datosReporte) {
            let gastos = 0;
            let ingresos = 0;
            datosReporte.forEach(function (dato) {
                if (dato.tipo === "Gasto") {
                    gastos += dato.monto;
                }
                if (dato.tipo === "Ingreso") {
                    ingresos += dato.monto;
                }
            });

            const balance = ingresos - gastos;

            graficoDistribucion.innerHTML =
                `
                <strong> Gastos:</strong><br>
                ₡${gastos.toLocaleString("es-CR")}
                `;
            graficoIngresos.innerHTML =
                `
                <strong> Ingresos:</strong><br>
                ₡${ingresos.toLocaleString("es-CR")}
                `;
            graficoBalance.innerHTML =
                `
                <strong> Balance:</strong><br>
                ₡${balance.toLocaleString("es-CR")}
                `;
        }

        // Descargar el reporte como PDF desde la impresión del navegador
        btnDescargar.addEventListener("click", function () {
            window.print();
        });
    });
    
/* Fin del script :D */
