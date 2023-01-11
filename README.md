# ![Greenter](https://cdn.giansalex.dev/images/github/greenter-ico.png) Greenter Demo
![CI](https://github.com/thegreenter/demo/workflows/CI/badge.svg)
[![GitHub repo size in bytes](https://img.shields.io/github/repo-size/thegreenter/demo.svg?style=flat-square)](https://github.com/thegreenter/demo)
[![GitHub issues](https://img.shields.io/github/issues/thegreenter/demo.svg?style=flat-square)](https://github.com/thegreenter/demo/issues)   
Ejemplos de comprobantes electrónicos empleando [Greenter](https://github.com/thegreenter/greenter).

:speech_balloon: Tienes preguntas, únete al [forum](https://community.greenter.dev/).

### Topics
- Generación de XML en el estándar UBL 2.0, 2.1
- Guia de Remisión Remitente (2022).
- Generación comprobantes de contingencia.
- Firma digital de XML
- Envío a servicio de SUNAT
- Procesamiento del CDR (Comprobante de Recepción)
- Extracción del Hash o Valor Resumen
- Representación Impresa, PDF, QR

### Pasos

**Requerimientos**
- `PHP 7.4` o superior
- Activar extensiones: `openssl`, `soap`, `curl`

Clonar el repositorio e instalar las dependencias.

```bash
git clone https://github.com/thegreenter/demo.git
cd demo
composer install --no-dev -o
```

### Ejecutar

Abrir la consola y ejecutar el siguiente comando.

```bash
php -S 0.0.0.0:8080
```

Finalmente navegar a http://localhost:8080/
> Los xml, pdf y cdr (archivos zip) seran guardados en la carptea `files`.

