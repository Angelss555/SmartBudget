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

        // Datos cargados
        const ingresos = 50000;
        const gastos = 10000;

        // Cálculo del balance
        const balance = ingresos - gastos;

        // Mostrar datos en pantalla
        document.getElementById("total-ingresos").textContent =
                "₡" + ingresos.toLocaleString("es-CR");

        document.getElementById("total-gastos").textContent =
                "₡" + gastos.toLocaleString("es-CR");

        document.getElementById("balance-disponible").textContent =
                "₡" + balance.toLocaleString("es-CR");

        // Mostrar alerta si los gastos son altos
        const alerta = document.getElementById("alerta-categoria");

        if (gastos >= 180000) {
            alerta.hidden = false;
        } else {
            alerta.hidden = true;
        }

        // Marcadores para futuros gráficos
        document.getElementById("grafico-categorias").innerHTML =
                "<strong>Aquí aparecerá el gráfico por categorías.</strong>";

        document.getElementById("grafico-tendencia").innerHTML =
                "<strong>Aquí aparecerá el gráfico de ingresos vs gastos.</strong>";
    });
    
 /*  ===================================================
 *      Html de gastos
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        // Referencias
        const form = document.getElementById("form-gasto");
        if (!form) return;

        const categoria = document.getElementById("categoria-gasto");
        const campoNueva = document.getElementById("campo-categoria-nueva");
        const nuevaCategoria = document.getElementById("categoria-nueva");
        const tbody = document.getElementById("tbody-gastos");

        // Mostrar u ocultar el campo de nueva categoría
        categoria.addEventListener("change", function () {

            if (this.value === "nueva") {
                campoNueva.hidden = false;
                nuevaCategoria.required = true;
            } else {
                campoNueva.hidden = true;
                nuevaCategoria.required = false;
                nuevaCategoria.value = "";
            }

        });

        // Registrar gasto
        form.addEventListener("submit", function (e) {

            e.preventDefault();

            const nombre = document.getElementById("nombre-gasto").value.trim();
            const monto = document.getElementById("monto-gasto").value;
            const frecuencia = document.getElementById("frecuencia-gasto").value;
            const fecha = document.getElementById("fecha-gasto").value;
            const descripcion = document.getElementById("descripcion-gasto").value;

            let categoriaTexto = categoria.options[categoria.selectedIndex].text;

            if (categoria.value === "nueva") {
                categoriaTexto = nuevaCategoria.value.trim();
            }

            // Validaciones
            if (
                nombre === "" ||
                monto === "" ||
                Number(monto) <= 0 ||
                fecha === "" ||
                categoriaTexto === ""
            ) {
                alert("Complete todos los campos obligatorios.");
                return;
            }

            // Eliminar mensaje de tabla vacía
            const filaVacia = document.querySelector(".fila-vacia");

            if (filaVacia) {
                filaVacia.remove();
            }

            // Crear fila
            const fila = document.createElement("tr");

            fila.innerHTML = `
                <td>${fecha}</td>
                <td>${nombre}</td>
                <td>${categoriaTexto}</td>
                <td>${frecuencia}</td>
                <td>₡${Number(monto).toLocaleString("es-CR")}</td>
                <td>
                    <button class="btn-eliminar">Eliminar</button>
                </td>
            `;
            tbody.appendChild(fila);
            
            // Botón eliminar
            fila.querySelector(".btn-eliminar").addEventListener("click", function () {

                fila.remove();

                if (tbody.children.length === 0) {

                    tbody.innerHTML = `
                        <tr class="fila-vacia">
                            <td colspan="6">
                                Todavía no hay gastos registrados.
                            </td>
                        </tr>
                    `;
                }
            });
            alert("Gasto registrado correctamente.");
            form.reset();
            campoNueva.hidden = true;
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
            e.preventDefault();

            const emailValue = email.value.trim();
            const passwordValue = password.value.trim();

            // Reset error
            mensajeError.hidden = true;

            // Validaciones básicas
            if (emailValue === "" || passwordValue === "") {
                mensajeError.textContent = "Por favor complete todos los campos.";
                mensajeError.hidden = false;
                return;
            }

            // Validación de formato de correo
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(emailValue)) {
                mensajeError.textContent = "Ingrese un correo válido.";
                mensajeError.hidden = false;
                return;
            }

            // SIMULACIÓN DE LOGIN 
            const usuarioDemo = "admin@smartbudget.com";
            const passDemo = "1234";

            if (emailValue === usuarioDemo && passwordValue === passDemo) {

                alert("Bienvenido a SmartBudget");

                // Redirección al dashboard
                window.location.href = "dashboard.html";

            } else {
                mensajeError.textContent = "Correo o contraseña incorrectos.";
                mensajeError.hidden = false;
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

        const tbody = document.querySelector("#tabla-ingresos tbody");

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const nombre = document.getElementById("nombre-ingreso").value.trim();
            const monto = document.getElementById("monto-ingreso").value;
            const tipo = document.getElementById("tipo-ingreso").value;
            const categoriaSelect = document.getElementById("categoria-ingreso");
            const categoria = categoriaSelect.options[categoriaSelect.selectedIndex].text;
            const fecha = document.getElementById("fecha-ingreso").value;

            // Validaciones
            if (
                nombre === "" ||
                monto === "" ||
                Number(monto) <= 0 ||
                tipo === "" ||
                categoria === "" ||
                fecha === ""
            ) {
                alert("Por favor complete todos los campos obligatorios.");
                return;
            }

            // Eliminar fila vacía si existe
            const filaVacia = document.querySelector(".fila-vacia");
            if (filaVacia) filaVacia.remove();

            // Crear nueva fila
            const fila = document.createElement("tr");

            fila.innerHTML = `
                <td>${fecha}</td>
                <td>${nombre}</td>
                <td>${categoria}</td>
                <td>${tipo}</td>
                <td>₡${Number(monto).toLocaleString("es-CR")}</td>
                <td>
                    <button class="btn-eliminar">Eliminar</button>
                </td>
            `;

            tbody.appendChild(fila);

            // Botón eliminar
            fila.querySelector(".btn-eliminar").addEventListener("click", function () {

                fila.remove();

                // Si no hay filas, mostrar mensaje vacío
                if (tbody.children.length === 0) {
                    tbody.innerHTML = `
                        <tr class="fila-vacia">
                            <td colspan="6">Todavía no hay ingresos registrados.</td>
                        </tr>
                    `;
                }
            });
            alert("Ingreso registrado correctamente");
            form.reset();
        });
    });

/*  ===================================================
 *      Html de metas
 *  ===================================================
*/
    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("form-meta");
        const listaMetas = document.getElementById("lista-metas");
        const mensajeVacio = document.getElementById("mensaje-sin-metas");

        if (!form) return;

        let contadorMetas = 0;

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const nombre = document.getElementById("nombre-meta").value.trim();
            const objetivo = document.getElementById("monto-objetivo").value;
            const fecha = document.getElementById("fecha-estimada").value;

            // Validaciones
            if (
                nombre === "" ||
                objetivo === "" ||
                Number(objetivo) <= 0 ||
                fecha === ""
            ) {
                alert("Por favor complete todos los campos correctamente.");
                return;
            }

            // Ocultar mensaje de vacío
            if (mensajeVacio) {
                mensajeVacio.style.display = "none";
            }

            // Crear ID único
            const id = contadorMetas++;

            // Crear tarjeta de meta
            const meta = document.createElement("article");
            meta.classList.add("tarjeta-meta");

            meta.innerHTML = `
                <div class="encabezado-meta">
                    <h3>${nombre}</h3>
                    <span class="fecha-meta">Meta: ${fecha}</span>
                </div>

                <div class="barra-progreso" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">

                    <div class="progreso" style="width: 0%;"></div>
                </div>

                <p class="detalle-meta">
                    <span id="ahorrado-${id}">₡0.00</span>
                    ahorrados de
                    <span id="objetivo-${id}">
                        ₡${Number(objetivo).toLocaleString("es-CR")}
                    </span>
                </p>

                <button class="btn-eliminar">Eliminar</button>
            `;

            listaMetas.appendChild(meta);

            // Botón eliminar
            meta.querySelector(".btn-eliminar").addEventListener("click", function () {
                meta.remove();

                // Si no quedan metas, mostrar mensaje
                if (document.querySelectorAll(".tarjeta-meta").length === 0) {
                    mensajeVacio.style.display = "block";
                }
            });

            alert("Meta de ahorro creada ");

            form.reset();
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
