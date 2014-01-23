# HTML to PDF converter

API
=========================== 
<a name="top"/>

Resource                           | Description
---------------------------------- | -------------
[GET /status](#get1)               | Get service status
[GET /pdf/batch/render/:id](#get2) | Render batch content (deprecated)
[POST /pdf/render](#post1)         | Render PDF
[POST /pdf/batch/render](#post2)   | Render batch content
[POST /pdf/batch/add](#post3)      | Add content to batch

<a name="get1"/>

### GET /status

```
curl -X GET 'http://example.com/status'
```

```text/plain
OK
```

[Вверх](#top)

<a name="get2"/>

### GET /pdf/batch/render/:id

```
curl -X GET 'http://example.com/pdf/batch/render/1354353464'
```

```application/pdf
PDF CONTENT
```

[Вверх](#top)

<a name="post1"/>

### POST /pdf/render


Required params: content
Optional params: page_size (A4, A5, A6, A7, A8), orientation (Portrait, Landscape)

```
curl --header 'Content-type: application/json' -X POST 'http://example.com/pdf/render' --data-binary '...'
```

```json
{
  "content": "<b>hello</b>",
  "page_size": "A4",
  "orientation": "Portrait"
}
```

Result

```application/pdf
PDF CONTENT
```

[Вверх](#top)

<a name="post2"/>

### POST /pdf/batch/render


Required params: transaction_id
Optional params: page_size (A4, A5, A6, A7, A8), orientation (Portrait, Landscape)

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

[Вверх](#top)

<a name="post3"/>

### POST /pdf/batch/add


Required params: content
Optional params: transaction_id (you receive transaction ID on first request and should use it with next requests)

```
curl --header 'Content-type: application/json' -X POST 'http://example.com/pdf/batch/add' '...'
```

```json
{
  "content": "<b>hello</b>",
}
```

Result

```application/json
[111222333444]
```

[Вверх](#top)
