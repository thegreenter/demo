# :tada: Greenter Samples :tada: 
[![GitHub last commit](https://img.shields.io/github/last-commit/giansalex/greenter-sample.svg?style=flat-square)](https://github.com/giansalex/greenter-sample) [![GitHub repo size in bytes](https://img.shields.io/github/repo-size/giansalex/greenter-sample.svg?style=flat-square)](https://github.com/giansalex/greenter-sample) [![GitHub issues](https://img.shields.io/github/issues/giansalex/greenter-sample.svg?style=flat-square)](https://github.com/giansalex/greenter-sample/issues)  
Ejemplos de envio de comprobantes electronicos empleando [Greenter](https://github.com/giansalex/greenter).

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

Clonar el repositorio e instalar las dependencias.

```bash
git clone https://github.com/giansalex/greenter-sample.git
cd greenter-sample
composer install --no-dev -o
```
**Permisos**   
Las carpetas `/cache` y `/files` requieren permisos de escritura.

### Ejecutar

Abrir la consola y ejecutar el siguiente comando.

```bash
php -S 0.0.0.0:8080
```

Finalmente navegar a http://localhost:8080
> Los xml, pdf y cdr (archivos zip) seran guardados en la carptea `data`, sino quiere que los archivos no se almacenen en su ordenador definir la variable de entorno `GREENTER_NO_FILES`

### Lista de Ejemplos
:white_check_mark: Factura (PDF) (UBL 2.1)    
:white_check_mark: Boleta de venta (PDF) (UBL 2.1)   
:white_check_mark: Nota de Crédito (PDF) (UBL 2.1)    
:white_check_mark: Nota de Débito  (PDF) (UBL 2.1)   
:white_check_mark: Resumen Diario de Boletas (PDF) (v2)    
:white_check_mark: Comunicación de Baja (PDF)  
:white_check_mark: Guia de Remisión  (PDF) (UBL 2.1)    
:white_check_mark: Retención (PDF)  
:white_check_mark: Perecepción (PDF)  
:white_check_mark: Resumen de Reversión (PDF)  
:white_check_mark: Consultar estado del CDR   
:ballot_box_with_check: Factura por Contingencia (UBL 2.1)   
:ballot_box_with_check: Resumen de Boletas por Contingencia (UBL 2.1)
:ballot_box_with_check: Factura con ICBPER (UBL 2.1)      
