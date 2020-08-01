# ![Greenter](https://cdn.giansalex.dev/images/github/greenter-ico.png) Greenter Demo
![CI](https://github.com/thegreenter/demo/workflows/CI/badge.svg)
[![GitHub repo size in bytes](https://img.shields.io/github/repo-size/thegreenter/demo.svg?style=flat-square)](https://github.com/thegreenter/demo)
[![GitHub issues](https://img.shields.io/github/issues/thegreenter/demo.svg?style=flat-square)](https://github.com/thegreenter/demo/issues)   
Ejemplos de envio de comprobantes electronicos empleando [Greenter](https://github.com/thegreenter/greenter).

> Si tienes dudas o necesitas consultar algo, puedes hacerlo [aquí](https://community.greenter.dev/).

### Topics
- Generación de XML UBL 2.0, UBL 2.1
- Generación comprobantes de contingencia.
- Firma del XML
- Compresión del XML en formato zip
- Envio a servicio de sunat
- Procesamiento de la respuesta (CDR)
- Extraccion del Hash de la Firma Digital
- Representacion Impresa - PDF

### Pasos

Clonar el repositorio e instalar las dependencias, se require `PHP 7.2` o superior

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

Finalmente navegar a http://localhost:8080
> Los xml, pdf y cdr (archivos zip) seran guardados en la carptea `files`.

### Lista de Ejemplos
:white_check_mark: Factura    
:white_check_mark: Boleta de venta   
:white_check_mark: Nota de Crédito    
:white_check_mark: Nota de Débito   
:white_check_mark: Resumen Diario de Boletas    
:white_check_mark: Comunicación de Baja 
:white_check_mark: Guia de Remisión    
:white_check_mark: Retención  
:white_check_mark: Perecepción  
:white_check_mark: Resumen de Reversión   
:white_check_mark: Consultar estado del CDR   
:ballot_box_with_check: Factura por Contingencia    
:ballot_box_with_check: Resumen de Boletas por Contingencia    
:ballot_box_with_check: Factura con ICBPER       
