# HTML to PDF converter

API
=========================== 
<a name="top"/>

Resource                           | Description
---------------------------------- | -------------
[GET /status](#get1)               | Get service status
[GET /pdf/batch/render/:id](#get2) | Render batch content (deprecated)
[POST /pdf/render](#post1)         | Render PDF
[POST /pdf/batch/add](#post3)      | Add content to batch
[POST /pdf/batch/render](#post2)   | Render batch content


<a name="get1"/>

### GET /status

```
curl -X GET 'http://example.com/status'
```

```text/plain
OK
```

[Top](#top)

<a name="get2"/>

### GET /pdf/batch/render/:id

!! Deprecated. Use `POST /pdf/batch/render` instead !!!

```
curl -X GET 'http://example.com/pdf/batch/render/1354353464'
```

```application/pdf
PDF CONTENT
```

[Top](#top)

<a name="post1"/>

### POST /pdf/render


Required params: 
  - content - HTML code
  
Optional params: 
  - page_size (A0, A1, A2, A3, A4, A5, A6)
  - orientation (Portrait, Landscape)
  - page_width (mm)
  - page_height (mm)
  - margin_left (mm)
  - margin_right (mm)
  - margin_top (mm)
  - margin_bottom (mm)

```
curl --header 'Content-type: application/json' -X POST 'http://example.com/pdf/render' --data-binary '...'
```

```json
{
  "content": "<b>hello</b>",
  "page_size": "A4",
  "orientation": "Portrait",
  "margin_left": "20mm"
}
```

Result

```application/pdf
PDF CONTENT
```

[Top](#top)

<a name="post3"/>

### POST /pdf/batch/add


Required params: 
 - content - HTML code

Optional params: 
 - transaction_id - batch ID, you'll receive it on first request and should use it with next requests.

```
curl --header 'Content-type: application/json' -X POST 'http://example.com/pdf/batch/add' '...'
```

```json
{
  "content": "<b>hello</b>"
}
```

Result

```application/json
[111222333444]
```

[Top](#top)

<a name="post2"/>

### POST /pdf/batch/render


Required params: 
 - transaction_id - batch ID

Optional params: 
  - page_size (A4, A5, A6)
  - orientation (Portrait, Landscape)
  - page_width (mm)
  - page_height (mm)
  - margin_left (mm)
  - margin_right (mm)
  - margin_top (mm)
  - margin_bottom (mm)

```
curl --header 'Content-type: application/json' -X POST 'http://example.com/pdf/batch/render' --data-binary '...'
```

```json
{
  "transaction_id": "111222333444",
  "page_size": "A4",
  "orientation": "Portrait"
}
```

Result

```application/pdf
PDF CONTENT
```

[Top](#top)
