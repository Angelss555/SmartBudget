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

        // Gráfico de ingresos y gastos últimos 6 meses
        const graficoSeisMeses = document.getElementById("grafico-seis-meses");

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

        //Datos de metas
        const metasSeisMesesData = document.getElementById("metas-seis-meses-data");//Obtiene el elemento del DOM que contiene los datos en formato JSON
        const metasSeisMesesArregloJavaScript = metasSeisMesesData//Convierte los datos JSON en un arreglo de JavaScript
            ? JSON.parse(metasSeisMesesData.textContent)
            : [];
        const mesesMetas = metasSeisMesesArregloJavaScript.map(dato => dato.mes);
        const montosMetasSeisMeses = metasSeisMesesArregloJavaScript.map(dato => dato.total);


        const montosDineroComprometido = montosGastoSeisMeses.map((gasto, indice) => gasto + (montosMetasSeisMeses[indice] || 0));


        new Chart(graficoCategorias, {
            type: "bar",

            data: {
                labels: categoriasGasto,

                datasets: [{
                    label: "Monto gastado",

                    data: montosGasto,

                    backgroundColor: "#9ca3af",
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
            e.preventDefault();
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

        // Simulación de descarga
        btnDescargar.addEventListener("click", function () {
            alert("Reporte descargado correctamente");
        });
    });
    
/* Fin del script :D */
