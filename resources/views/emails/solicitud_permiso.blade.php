<h2 style="font-family: Arial, sans-serif; color: #2c3e50;">📩 Solicitud de Permiso</h2>

<p style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
El profesional <strong>{{ $profesional->name }}</strong> ha solicitado acceso a la sección 
<strong>{{ $seccion->nombre }}</strong>.
</p>

<p style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
Por favor, revise la solicitud y apruebe el permiso si corresponde.
</p>

<p style="margin: 20px 0;">
    <a href="{{ $link }}" 
       style="background-color: #3498db; color: #fff; padding: 12px 20px; 
              text-decoration: none; border-radius: 5px; font-weight: bold;">
        Aprobar Permiso
    </a>
</p>

<p style="font-family: Arial, sans-serif; font-size: 13px; color: #555;">
Al aprobar la solicitud, se enviará automáticamente un correo al profesional con el código de acceso de un solo uso.
</p>
