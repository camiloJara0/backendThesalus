# Análisis de Tabla de Pacientes y Propuesta de Estructura de BD

**Fecha de Análisis:** Febrero 17, 2026  
**Base:** Arquitectura actual de Thesalus

---

## 1. Mapeo de Campos Actuales a Tablas Existentes

### Campos que YA EXISTEN en tu BD:

| Campo en Tabla | Tabla BD Actual | Columna | Observación |
|---|---|---|---|
| EPS | PACIENTES | id_eps | ✓ FK a tabla EPS |
| NOMBRE | INFORMACION_USERS | name | ✓ A través de relación |
| TIPO DOC | INFORMACION_USERS | type_doc | ✓ |
| DOCUMENTO | INFORMACION_USERS | No_document | ✓ UNIQUE |
| N. Tel (celular) | INFORMACION_USERS | celular | ✓ |
| DIRECCION | INFORMACION_USERS | direccion | ✓ |
| Barrio | INFORMACION_USERS | barrio | ✓ |
| Fecha Nto | INFORMACION_USERS | nacimiento | ✓ |
| Municipio Atencion | INFORMACION_USERS | municipio | ✓ |
| Regimen | PACIENTES | regimen | ✓ |
| Correo | USERS | correo | ✓ A través de id_infoUsuario |
| Estado | PACIENTES | estado | ✓ |
| Fecha Inicio | CITAS | fecha | ✓ O HISTORIA_CLINICAS.fecha_historia |
| Diagnostico | DIAGNOSTICOS | descripcion | ✓ FK a ANALISES |

**✓ Conclusión:** Todos estos campos YA EXISTEN, solo necesitas JOINs adecuados.

---

## 2. Campos CALCULABLES (No necesitan nueva tabla)

| Campo | Fórmula | Query Sugerida |
|---|---|---|
| **Fecha última visita médica** | MAX de citas/análisis | `SELECT MAX(fecha) FROM citas WHERE id_paciente = X` |
| **Mes** | MONTH(fecha última visita) | `SELECT MONTH(MAX(fecha)) FROM citas WHERE id_paciente = X` |

**Conclusión:** Estos campos NO requieren almacenamiento, se calculan dinámicamente.

---

## 3. Campos NUEVOS que REQUIEREN nuevas tablas

Estos campos están en tu tabla pero NO existen en tu arquitectura actual:

### 3.1. EQUIPOS DE SOPORTE MÉDICO (Kit Cateterismo, Kit Sonda, Kit Gastro, etc.)

| Campo | Tipo | Observación |
|---|---|---|
| Kit Cateterismo | BOOLEAN o FLAG | ¿El paciente usa? |
| Kit Sonda | BOOLEAN | ¿El paciente usa? |
| Kit Gastro | BOOLEAN | ¿El paciente usa? |
| Traqueo | BOOLEAN | ¿El paciente usa? |
| Oxígeno | BOOLEAN | ¿El paciente usa? |
| VM (Ventilador Mecánico) | BOOLEAN | ¿El paciente usa? |

**⚠️ Problema:** Tu tabla `PLAN_MANEJO_EQUIPOS` solo tiene descripción + uso, pero no diferencia tipos ni cantidad.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE equipos_soporte_medico (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_historia_clinica BIGINT NULLABLE,
  tipo_equipo VARCHAR(100) NOT NULL,  -- 'cateterismo', 'sonda', 'gastro', 'traqueo', 'vm', 'oxigeno'
  cantidad INT DEFAULT 1,
  descripcion TEXT NULLABLE,
  fecha_asignacion DATE NOT NULL,
  fecha_retiro DATE NULLABLE,
  estado ENUM('activo', 'inactivo', 'retirado') DEFAULT 'activo',
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_historia_clinica) REFERENCES historia_clinicas(id) ON DELETE SET NULL,
  INDEX(id_paciente, estado),
  INDEX(tipo_equipo)
);
```

---

### 3.2. SERVICIOS DE TERAPIA Y ESPECIALIDADES

Los campos de terapias en tu tabla:
- Terapeuta Respiratoria (TR)
- Terapia Física (TF)
- Terapia Fonoaudiología (TFO)
- Terapia Ocupacional (TO)
- Nutricionista
- Psicología
- Trabajo Social
- Guía Espiritual

**⚠️ Problema:** Tu tabla `TERAPIA` existe pero es muy genérica. Necesitas tabla de asignaciones.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE asignacion_terapias_especialidades (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_historia_clinica BIGINT NULLABLE,
  especialidad VARCHAR(100) NOT NULL,  
  -- 'terapeuta_respiratoria', 'terapia_fisica', 'terapia_fonoaudio', 
  -- 'terapia_ocupacional', 'nutricion', 'psicologia', 'trabajo_social', 'espiritual'
  profesional_asignado BIGINT NULLABLE,  -- FK a PROFESIONALS
  fecha_asignacion DATE NOT NULL,
  fecha_finalizacion DATE NULLABLE,
  cantidad_sesiones_asignadas INT DEFAULT 0,
  cantidad_sesiones_realizadas INT DEFAULT 0,
  estado ENUM('asignado', 'en_progreso', 'completado', 'cancelado') DEFAULT 'asignado',
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_historia_clinica) REFERENCES historia_clinicas(id) ON DELETE SET NULL,
  FOREIGN KEY(profesional_asignado) REFERENCES profesionals(id) ON DELETE SET NULL,
  INDEX(id_paciente, especialidad, estado),
  INDEX(profesional_asignado)
);
```

---

### 3.3. INFORMACIÓN DE CUIDADORES

Campo en tabla: **Cuidadores**

**⚠️ Problema:** No existe tabla para registrar cuidadores del paciente.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE cuidadores_paciente (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  parentesco VARCHAR(100),  -- 'familiar', 'contratado', 'voluntario'
  tipo_cuidador VARCHAR(100) NOT NULL,  -- 'principal', 'alterno', 'temporal'
  documento VARCHAR(20) NULLABLE,
  celular VARCHAR(20) NULLABLE,
  telefono VARCHAR(20) NULLABLE,
  correo VARCHAR(100) NULLABLE,
  direccion TEXT NULLABLE,
  fecha_inicio DATE NOT NULL,
  fecha_retiro DATE NULLABLE,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo',
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  INDEX(id_paciente, estado)
);
```

---

### 3.4. TIPO DE HERIDA Y COMPLEJIDAD

Campos: **Tipo de Herida**, **Complejidad**, **Profesional TEO**, **Observación TEO**

**⚠️ Problema:** No hay tabla para clasificar tipo y complejidad de heridas.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE heridas_paciente (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_analisis BIGINT NULLABLE,
  tipo_herida VARCHAR(100) NOT NULL,  
  -- 'quirurgica', 'traumatica', 'cronica', 'diabetes', 'ulcera_presion', etc.
  complejidad ENUM('baja', 'media', 'alta', 'critica') NOT NULL,
  localizacion VARCHAR(255),
  fecha_diagnostico DATE NOT NULL,
  profesional_teo BIGINT NULLABLE,  -- FK a PROFESIONALS
  estado_cicatrizacion VARCHAR(100),  -- 'inicial', 'cicatrizacion', 'cicatrizada'
  descripcion TEXT NOT NULL,
  observaciones_teo TEXT,
  fecha_evaluacion_teo DATE NULLABLE,
  creada_por BIGINT NULLABLE,  -- FK a USERS/PROFESIONALS
  fecha_cierre DATE NULLABLE,
  estado ENUM('abierta', 'cerrada', 'en_tratamiento') DEFAULT 'en_tratamiento',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_analisis) REFERENCES analises(id) ON DELETE SET NULL,
  FOREIGN KEY(profesional_teo) REFERENCES profesionals(id) ON DELETE SET NULL,
  FOREIGN KEY(creada_por) REFERENCES users(id) ON DELETE SET NULL,
  INDEX(id_paciente, estado),
  INDEX(complejidad)
);
```

---

### 3.5. ORDENES DE LABORATORIO Y RESULTADOS

Campos: **Orden de laboratorio**, **Fecha de resultado**, **Pago como rural**

**⚠️ Problema:** No hay tabla de laboratorio, solo genéricos.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE ordenes_laboratorio (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_analisis BIGINT NULLABLE,
  id_medico BIGINT NOT NULL,  -- FK a PROFESIONALS
  numero_orden VARCHAR(50) UNIQUE NOT NULL,
  tipo_examen VARCHAR(255) NOT NULL,  -- 'hemograma', 'bioquimica', 'cultivo', etc.
  descripcion TEXT NULLABLE,
  fecha_orden DATE NOT NULL,
  fecha_muestra DATE NULLABLE,
  fecha_resultado DATE NULLABLE,
  resultado_archivo_url VARCHAR(500) NULLABLE,  -- URL a resultado en PDF/imagen
  resultado_json JSON NULLABLE,  -- Resultados estructurados
  estado ENUM('pendiente', 'en_proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_analisis) REFERENCES analises(id) ON DELETE SET NULL,
  FOREIGN KEY(id_medico) REFERENCES profesionals(id) ON DELETE RESTRICT,
  INDEX(id_paciente, fecha_resultado),
  INDEX(numero_orden)
);
```

---

### 3.6. GESTIÓN DE MEDICAMENTOS MEJORADA

**⚠️ Problema:** Tu tabla `PLAN_MANEJO_MEDICAMENTOS` solo almacena nombre como string, sin validación de catálogo.

**Recomendación:** Agregar tabla catálogo:

```sql
CREATE TABLE medicamentos_catalogo (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(255) NOT NULL UNIQUE,
  codigo_interno VARCHAR(50) UNIQUE,
  principio_activo VARCHAR(255),
  presentacion VARCHAR(100),  -- 'tableta', 'capsula', 'liquido', 'inyectable'
  concentracion VARCHAR(100),  -- '500mg', '250ml'
  laboratorio VARCHAR(255),
  precio_unitario DECIMAL(10,2) DEFAULT 0,
  estado INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX(nombre),
  INDEX(codigo_interno)
);

-- Modificar tabla existente
ALTER TABLE plan_manejo_medicamentos
  ADD COLUMN id_medicamento BIGINT NULLABLE,
  ADD FOREIGN KEY(id_medicamento) REFERENCES medicamentos_catalogo(id) ON DELETE RESTRICT;
```

---

### 3.7. MONITOREO Y VISITAS MÉDICAS

Campos: **Fecha de la llamada y hora**, **Fecha última visita médica**

**⚠️ Problema:** No hay tabla específica para registro de contactos/visitas.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE monitoreo_paciente (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  tipo_contacto ENUM('llamada', 'visita_medica', 'videollamada', 'chat', 'email') NOT NULL,
  fecha_contacto DATE NOT NULL,
  hora_contacto TIME NOT NULL,
  profesional_id BIGINT,  -- FK a PROFESIONALS
  motivo_contacto TEXT NOT NULL,
  resultado VARCHAR(255),  -- 'exitoso', 'no_disponible', 'pendiente'
  tiempo_duracion INT,  -- en minutos
  observaciones TEXT,
  estado ENUM('completado', 'pendiente', 'cancelado') DEFAULT 'completado',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(profesional_id) REFERENCES profesionals(id) ON DELETE SET NULL,
  INDEX(id_paciente, fecha_contacto),
  INDEX(tipo_contacto),
  INDEX(tipo_contacto, id_paciente)  -- Para encontrar última visita médica
);
```

---

### 3.8. EQUIPO INTERDISCIPLINARIO Y ASIGNACIONES

Campos: **Enfermería Jefe**, **Médico Internista**, **Médico Fisiatra**, **Medicina Familiar**, **Auxiliar Enfermería**

**⚠️ Problema:** No hay tabla que gestione equipo multidisciplinario.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE equipo_multidisciplinario_paciente (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_historia_clinica BIGINT NULLABLE,
  rol_equipo VARCHAR(100) NOT NULL,  
  -- 'jefe_enfermeria', 'medico_internista', 'medico_fisiatra', 
  -- 'medicina_familiar', 'auxiliar_enfermeria', 'coordinador'
  profesional_id BIGINT NOT NULL,  -- FK a PROFESIONALS
  fecha_asignacion DATE NOT NULL,
  fecha_retiro DATE NULLABLE,
  responsabilidades TEXT,
  estado ENUM('activo', 'inactivo', 'retirado') DEFAULT 'activo',
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_historia_clinica) REFERENCES historia_clinicas(id) ON DELETE SET NULL,
  FOREIGN KEY(profesional_id) REFERENCES profesionals(id) ON DELETE RESTRICT,
  UNIQUE KEY unique_rol_activo (id_paciente, rol_equipo, id_historia_clinica),
  INDEX(profesional_id),
  INDEX(rol_equipo)
);
```

---

### 3.9. ADMINISTRACIÓN DE MANTENIMIENTOS

Campo: **ADMON MTOS** (Administración mantenimientos)

**⚠️ Problema:** No hay tabla para gestionar mantenimiento de equipos.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE mantenimiento_equipos_paciente (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_equipo BIGINT,  -- FK a equipos_soporte_medico
  tipo_mantenimiento VARCHAR(100) NOT NULL,  -- 'preventivo', 'correctivo', 'calibracion'
  equipo_nombre VARCHAR(255),
  fecha_programada DATE NOT NULL,
  fecha_realizado DATE NULLABLE,
  responsable BIGINT,  -- FK a PROFESIONALS/USERS
  descripcion TEXT,
  costo DECIMAL(10,2) NULLABLE,
  estado ENUM('pendiente', 'completado', 'cancelado') DEFAULT 'pendiente',
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_equipo) REFERENCES equipos_soporte_medico(id) ON DELETE SET NULL,
  FOREIGN KEY(responsable) REFERENCES users(id) ON DELETE SET NULL,
  INDEX(id_paciente, estado),
  INDEX(fecha_programada)
);
```

---

### 3.10. PAGOS Y FACTURACIÓN POR PACIENTE

Campo: **Pago como rural**

**⚠️ Problema:** No hay información de cobro por paciente/servicio.

**Propuesta de tabla nueva:**

```sql
CREATE TABLE cobros_paciente (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  id_paciente BIGINT NOT NULL,
  id_facturacion BIGINT NULLABLE,  -- FK a FACTURACIONS
  concepto VARCHAR(255) NOT NULL,  -- 'consulta', 'terapia', 'equipo', etc.
  monto_total DECIMAL(12,2) NOT NULL,
  valor_paciente DECIMAL(12,2) DEFAULT 0,
  valor_aseguradora DECIMAL(12,2) DEFAULT 0,
  tipo_pago ENUM('rural', 'urbano', 'subsidiado', 'contributivo') NOT NULL,
  estado_pago ENUM('pendiente', 'parcial', 'pagado', 'incobrable') DEFAULT 'pendiente',
  fecha_servicio DATE NOT NULL,
  fecha_cobro DATE NULLABLE,
  observaciones TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY(id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY(id_facturacion) REFERENCES facturacions(id) ON DELETE SET NULL,
  INDEX(id_paciente, estado_pago),
  INDEX(tipo_pago)
);
```

---

## 4. RESUMEN DE NUEVAS TABLAS PROPUESTAS

| # | Tabla Nueva | Propósito | Registros Esperados |
|---|---|---|---|
| 1 | `equipos_soporte_medico` | Equipos asignados al paciente | M (muchos por paciente) |
| 2 | `asignacion_terapias_especialidades` | Asignación de terapistas | M (5-10 por paciente) |
| 3 | `cuidadores_paciente` | Registro de cuidadores | B (1-3 por paciente) |
| 4 | `heridas_paciente` | Tipo y complejidad de heridas | B-M (1-5 por paciente) |
| 5 | `ordenes_laboratorio` | Órdenes y resultados | M (10+ por paciente) |
| 6 | `medicamentos_catalogo` | Catálogo de medicamentos | P (válido para 1000s) |
| 7 | `monitoreo_paciente` | Contactos y visitas | M (diarios) |
| 8 | `equipo_multidisciplinario_paciente` | Asignación de equipo ✓ | B (5-15 roles) |
| 9 | `mantenimiento_equipos_paciente` | Mantenimiento equipo médico | B-M |
| 10 | `cobros_paciente` | Facturación por servicios | M (diarios) |

**Leyenda:** P=Pocas, B=Bajas (decenas), M=Muchas (cientos), ✓=Prioritaria

---

## 5. QUERY PROPUESTA PARA OBTENER TODA LA INFORMACIÓN

```sql
SELECT 
  -- Datos personales (INFORMACION_USERS → PACIENTES)
  iu.name as 'NOMBRE',
  iu.type_doc as 'TIPO DOC',
  iu.No_document as 'DOCUMENTO',
  iu.celular as 'N. Tel',
  iu.direccion as 'DIRECCION',
  iu.barrio as 'Barrio',
  iu.nacimiento as 'Fecha Nto',
  iu.municipio as 'Municipio Atencion',
  
  -- Datos del paciente (PACIENTES)
  eps.nombre as 'EPS',
  p.regimen as 'Regimen',
  p.estado as 'Estado',
  
  -- Datos de contacto (USERS)
  u.correo as 'Correo',
  
  -- Diagnóstico (DIAGNOSTICOS + ANALISES)
  d.descripcion as 'Diagnostico',
  MAX(CASE WHEN a.id IS NOT NULL THEN a.created_at END) as 'Fecha Inicio',
  
  -- Fecha última visita (MONITOREO - CALCULADO)
  MAX(CASE WHEN m.tipo_contacto = 'visita_medica' THEN m.fecha_contacto END) as 'Fecha ultima visita medica',
  MONTH(MAX(CASE WHEN m.tipo_contacto = 'visita_medica' THEN m.fecha_contacto END)) as 'Mes',
  
  -- Equipos (EQUIPOS_SOPORTE_MEDICO)
  MAX(CASE WHEN esm.tipo_equipo = 'cateterismo' THEN 'SI' ELSE 'NO' END) as 'Kit Cateterismo',
  MAX(CASE WHEN esm.tipo_equipo = 'sonda' THEN 'SI' ELSE 'NO' END) as 'Kit sonda',
  MAX(CASE WHEN esm.tipo_equipo = 'gastro' THEN 'SI' ELSE 'NO' END) as 'Kit gastro',
  MAX(CASE WHEN esm.tipo_equipo = 'traqueo' THEN 'SI' ELSE 'NO' END) as 'Traqueo',
  MAX(CASE WHEN esm.tipo_equipo = 'oxigeno' THEN 'SI' ELSE 'NO' END) as 'Oxigeno',
  MAX(CASE WHEN esm.tipo_equipo = 'vm' THEN 'SI' ELSE 'NO' END) as 'VM',
  
  -- Terapias (ASIGNACION_TERAPIAS_ESPECIALIDADES)
  MAX(CASE WHEN ate.especialidad = 'terapeuta_respiratoria' THEN pr1.id_infoUsuario END) as 'TR',
  MAX(CASE WHEN ate.especialidad = 'terapia_fisica' THEN pr2.id_infoUsuario END) as 'TF',
  MAX(CASE WHEN ate.especialidad = 'terapia_fonoaudio' THEN pr3.id_infoUsuario END) as 'TFO',
  MAX(CASE WHEN ate.especialidad = 'terapia_ocupacional' THEN pr4.id_infoUsuario END) as 'TO',
  MAX(CASE WHEN ate.especialidad = 'nutricion' THEN pr5.id_infoUsuario END) as 'Nutricionista',
  MAX(CASE WHEN ate.especialidad = 'psicologia' THEN pr6.id_infoUsuario END) as 'VPSico',
  MAX(CASE WHEN ate.especialidad = 'trabajo_social' THEN pr7.id_infoUsuario END) as 'T social',
  MAX(CASE WHEN ate.especialidad = 'espiritual' THEN pr8.id_infoUsuario END) as 'Guia Espiritual',
  
  -- Equipo multidisciplinario (EQUIPO_MULTIDISCIPLINARIO_PACIENTE)
  MAX(CASE WHEN emp.rol_equipo = 'jefe_enfermeria' THEN pr9.id_infoUsuario END) as 'Enfermeria Jefe',
  MAX(CASE WHEN emp.rol_equipo = 'medico_internista' THEN pr10.id_infoUsuario END) as 'Medico Internista',
  MAX(CASE WHEN emp.rol_equipo = 'medico_fisiatra' THEN pr11.id_infoUsuario END) as 'Medico Fisiatra',
  MAX(CASE WHEN emp.rol_equipo = 'medicina_familiar' THEN pr12.id_infoUsuario END) as 'Medicina Familiar',
  MAX(CASE WHEN emp.rol_equipo = 'auxiliar_enfermeria' THEN pr13.id_infoUsuario END) as 'Auxiliar de Enfermeria',
  
  -- Heridas (HERIDAS_PACIENTE)
  MAX(CASE WHEN hp.id IS NOT NULL THEN hp.tipo_herida END) as 'Tipo de Herida',
  MAX(CASE WHEN hp.id IS NOT NULL THEN hp.complejidad END) as 'Complejidad',
  MAX(CASE WHEN hp.id IS NOT NULL THEN pr14.id_infoUsuario END) as 'Profesional TEO',
  MAX(CASE WHEN hp.id IS NOT NULL THEN hp.observaciones_teo END) as 'Observacion TEO',
  
  -- Laboratorio (ORDENES_LABORATORIO)
  MAX(CASE WHEN ol.id IS NOT NULL THEN ol.numero_orden END) as 'Orden de laboratorio',
  MAX(CASE WHEN ol.id IS NOT NULL THEN ol.fecha_resultado END) as 'Fecha de resultado',
  
  -- Cobros (COBROS_PACIENTE)
  MAX(CASE WHEN cp.tipo_pago = 'rural' THEN 'SI' ELSE 'NO' END) as 'Pago como rural',
  
  -- Contactos (MONITOREO_PACIENTE)
  MAX(CASE WHEN m.id IS NOT NULL THEN CONCAT(DATE(m.fecha_contacto), ' ', m.hora_contacto) END) as 'Fecha de la llamada y hora',
  
  -- Observaciones
  MAX(CASE WHEN m.id IS NOT NULL THEN m.observaciones END) as 'Observacion'
  
FROM pacientes p
LEFT JOIN informacion_users iu ON p.id_infoUsuario = iu.id
LEFT JOIN users u ON iu.id = u.id_infoUsuario
LEFT JOIN eps ON p.id_eps = eps.id
LEFT JOIN historia_clinicas hc ON p.id = hc.id_paciente
LEFT JOIN analises a ON hc.id = a.id_historia
LEFT JOIN diagnosticos d ON a.id = d.id_analisis
LEFT JOIN equipos_soporte_medico esm ON p.id = esm.id_paciente AND esm.estado = 'activo'
LEFT JOIN asignacion_terapias_especialidades ate ON p.id = ate.id_paciente AND ate.estado IN ('asignado', 'en_progreso')
LEFT JOIN profesionals pr1 ON ate.profesional_asignado = pr1.id AND ate.especialidad = 'terapeuta_respiratoria'
LEFT JOIN profesionals pr2 ON ate.profesional_asignado = pr2.id AND ate.especialidad = 'terapia_fisica'
LEFT JOIN profesionals pr3 ON ate.profesional_asignado = pr3.id AND ate.especialidad = 'terapia_fonoaudio'
LEFT JOIN profesionals pr4 ON ate.profesional_asignado = pr4.id AND ate.especialidad = 'terapia_ocupacional'
LEFT JOIN profesionals pr5 ON ate.profesional_asignado = pr5.id AND ate.especialidad = 'nutricion'
LEFT JOIN profesionals pr6 ON ate.profesional_asignado = pr6.id AND ate.especialidad = 'psicologia'
LEFT JOIN profesionals pr7 ON ate.profesional_asignado = pr7.id AND ate.especialidad = 'trabajo_social'
LEFT JOIN profesionals pr8 ON ate.profesional_asignado = pr8.id AND ate.especialidad = 'espiritual'
LEFT JOIN equipo_multidisciplinario_paciente emp ON p.id = emp.id_paciente AND emp.estado = 'activo'
LEFT JOIN profesionals pr9 ON emp.profesional_id = pr9.id AND emp.rol_equipo = 'jefe_enfermeria'
LEFT JOIN profesionals pr10 ON emp.profesional_id = pr10.id AND emp.rol_equipo = 'medico_internista'
LEFT JOIN profesionals pr11 ON emp.profesional_id = pr11.id AND emp.rol_equipo = 'medico_fisiatra'
LEFT JOIN profesionals pr12 ON emp.profesional_id = pr12.id AND emp.rol_equipo = 'medicina_familiar'
LEFT JOIN profesionals pr13 ON emp.profesional_id = pr13.id AND emp.rol_equipo = 'auxiliar_enfermeria'
LEFT JOIN heridas_paciente hp ON p.id = hp.id_paciente AND hp.estado IN ('abierta', 'en_tratamiento')
LEFT JOIN profesionals pr14 ON hp.profesional_teo = pr14.id
LEFT JOIN ordenes_laboratorio ol ON p.id = ol.id_paciente
LEFT JOIN monitoreo_paciente m ON p.id = m.id_paciente
LEFT JOIN cobros_paciente cp ON p.id = cp.id_paciente

WHERE p.estado = 1
GROUP BY p.id, iu.id, u.id;
```

---

## 6. DIAGRAMA ENTIDAD-RELACIÓN (Nuevas Tablas)

```
PACIENTES (existente)
    │
    ├─→ equipos_soporte_medico (NEW)
    │    └─ Tipos: cateterismo, sonda, gastro, traqueo, vm, oxigeno
    │
    ├─→ asignacion_terapias_especialidades (NEW)
    │    ├─ Especialidades: TR, TF, TFO, TO, Nutrición, Psicología, Trabajo Social, Espiritual
    │    └─→ profesionals (existente)
    │
    ├─→ cuidadores_paciente (NEW)
    │    └─ Datos de cuidadores
    │
    ├─→ equipo_multidisciplinario_paciente (NEW)
    │    ├─ Roles: Jefe Enfermería, Médico Internista, Médico Fisiatra, Medicina Familiar, Auxiliar
    │    └─→ profesionals (existente)
    │
    ├─→ heridas_paciente (NEW)
    │    └─ Tipo, Complejidad, Profesional TEO
    │
    ├─→ ordenes_laboratorio (NEW)
    │    ├─ Orden, Resultados, Fechas
    │    └─→ profesionals (id_medico)
    │
    ├─→ mantenimiento_equipos_paciente (NEW)
    │    └─ Mantenimiento de equipos médicos
    │
    ├─→ monitoreo_paciente (NEW)
    │    ├─ Llamadas, Visitas Médicas, Videoconferencias
    │    └─→ **Para calcular "Fecha última visita médica" y "Mes"**
    │
    └─→ cobros_paciente (NEW)
         └─ Pagos como rural/urbano

medicamentos_catalogo (NEW)
    └─ Catálogo centralizado de medicamentos
```

---

## 7. ORDEN DE IMPLEMENTACIÓN RECOMENDADO

### Fase 1 (Crítica - Primera semana)
1. `equipos_soporte_medico` - Base para equipos
2. `asignacion_terapias_especialidades` - Terapias
3. `monitoreo_paciente` - Para calcular visita última

### Fase 2 (Importante - Segunda semana)
4. `equipo_multidisciplinario_paciente` - Equipo médico
5. `heridas_paciente` - Gestión de heridas
6. `cuidadores_paciente` - Seguimiento de cuidadores

### Fase 3 (Complementaria - Tercer semana)
7. `ordenes_laboratorio` - Laboratorio
8. `medicamentos_catalogo` - Catálogo medicamentos
9. `mantenimiento_equipos_paciente` - Mantenimiento
10. `cobros_paciente` - Facturación por paciente

---

## 8. OBSERVACIONES IMPORTANTES

✅ **Lo que PUEDES CALCULAR (sin tabla nueva):**
- Fecha última visita médica → `MAX(fecha) FROM citas`
- Mes → `MONTH(fecha)`
- Edad → `YEAR(NOW()) - YEAR(nacimiento)`

❌ **Lo que NECESITA TABLA (porque no está en tu BD):**
- Equipos utilizados (cateterismo, sonda, etc.)
- Asignación de terapistas específicos
- Tipo y complejidad de heridas
- Ordenes de laboratorio
- Cuidadores del paciente

⚠️ **MEJORAS RECOMENDADAS A TABLAS EXISTENTES:**
- `PLAN_MANEJO_MEDICAMENTOS`: Agregar FK a `medicamentos_catalogo`
- `PLAN_MANEJO_EQUIPOS`: Agregar tipos y cantidad
- `TERAPIA`: Ser más específico (especialidad + profesional)

---

**Documento preparado para implementación migrations Laravel**
