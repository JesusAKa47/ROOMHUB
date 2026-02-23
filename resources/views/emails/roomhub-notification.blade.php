<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>{{ $title ?? 'RoomHub' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* Estilos básicos para clientes que sí respetan <style> */
    @media only screen and (max-width: 600px) {
      .container {
        width: 100% !important;
        padding: 16px !important;
      }
      .card {
        padding: 20px !important;
      }
    }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#050510; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

  <!-- Fondo general -->
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#050510; padding:24px 0;">
    <tr>
      <td align="center">

        <!-- Contenedor -->
        <table class="container" width="600" border="0" cellspacing="0" cellpadding="0" style="width:600px; max-width:100%;">

          <!-- Header / Logo -->
          <tr>
            <td align="left" style="padding:0 24px 12px 24px;">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="middle" width="36" height="36"
                      style="border-radius:12px; background:linear-gradient(135deg,#4f46e5,#22c55e); text-align:center;">
                    <!-- Icono simple -->
                    <span style="display:inline-block; font-size:18px; color:#ffffff; font-weight:bold;">RH</span>
                  </td>
                  <td style="padding-left:10px;">
                    <span style="font-size:18px; color:#f9fafb; font-weight:600;">RoomHub</span><br>
                    <span style="font-size:11px; color:#9ca3af;">Gestión inteligente de departamentos</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Card -->
          <tr>
            <td style="padding:0 24px;">
              <table class="card" width="100%" border="0" cellspacing="0" cellpadding="0"
                     style="border-radius:20px; background:linear-gradient(145deg,#0b0b15,#050510); border:1px solid #1f2937; padding:28px;">
                <tr>
                  <td>

                    <!-- Título -->
                    <h1 style="margin:0 0 12px 0; font-size:22px; line-height:1.3; color:#f9fafb;">
                      {{ $title ?? 'Notificación de RoomHub' }}
                    </h1>

                    <!-- Texto principal -->
                    <p style="margin:0 0 10px 0; font-size:14px; line-height:1.6; color:#d1d5db;">
                      {{ $intro ?? 'Te enviamos esta notificación desde RoomHub.' }}
                    </p>

                    @isset($extraLines)
                      @foreach($extraLines as $line)
                        <p style="margin:0 0 10px 0; font-size:14px; line-height:1.6; color:#d1d5db;">
                          {{ $line }}
                        </p>
                      @endforeach
                    @endisset

                    <!-- Botón principal -->
                    @isset($buttonUrl)
                      <table border="0" cellspacing="0" cellpadding="0" style="margin:24px 0 10px 0;">
                        <tr>
                          <td align="center">
                            <a href="{{ $buttonUrl }}"
                               style="display:inline-block; padding:12px 24px; border-radius:999px;
                                      background:linear-gradient(135deg,#4f46e5,#22c55e);
                                      color:#f9fafb; text-decoration:none; font-size:14px; font-weight:600;">
                              {{ $buttonText ?? 'Abrir en RoomHub' }}
                            </a>
                          </td>
                        </tr>
                      </table>
                    @endisset

                    <!-- Nota secundaria -->
                    @isset($subcopy)
                      <p style="margin:18px 0 0 0; font-size:12px; line-height:1.6; color:#9ca3af;">
                        {{ $subcopy }}
                      </p>
                    @endisset

                    <!-- Enlace plano por si el botón no sirve -->
                    @isset($buttonUrl)
                      <p style="margin:18px 0 0 0; font-size:11px; line-height:1.6; color:#6b7280;">
                        Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                        <span style="word-break:break-all; color:#9ca3af;">{{ $buttonUrl }}</span>
                      </p>
                    @endisset

                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding:16px 24px 0 24px;">
              <p style="margin:0; font-size:11px; line-height:1.6; color:#6b7280;">
                © {{ date('Y') }} RoomHub. Todos los derechos reservados.<br>
                Este es un correo automático, por favor no respondas directamente a este mensaje.
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
