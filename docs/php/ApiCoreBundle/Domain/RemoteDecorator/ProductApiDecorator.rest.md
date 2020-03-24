# HTTP ProductApiDecorator

This class executes REST calls using a configured formatter and configured
endpoint URLs.

## `POST` `https://example.com/beforeGetCategories`

*Before Decorator for getCategories*

Adapt the categories query before the query is executed against the
backend. If nothing is returned the original arguments will be used.
The URL and method can actually be configured by you.

### Request Body

```
[CategoryQuery]
```

### Responses

Status: 200

Adapt the categories query before the query is executed against the backend.
If nothing is returned the original arguments will be used. The URL and method
can actually be configured by you.

```
?[CategoryQuery]
```

## `POST` `https://example.com/afterGetCategories`

*After Decorator for getCategories*

Adapt the categories result. If nothing is returned the original result
will be used. The URL and method can actually be configured by you.

### Request Body

```
Category[]
```

### Responses

Status: 200

Adapt the categories result. If nothing is returned the original result will
be used. The URL and method can actually be configured by you.

```
?Category[]
```

