# TEST API PHP GENERATE & VERIFY TOKEN

### Running Development Server
```bash
php -S 0.0.0.0:8000
```
<br>


### GENERATE TOKEN
```bash
[POST]	http://localhost:8000/otp/generate
```
Body Data

```json
{
	"user_id": "<random_user_id>"
}
```

<br>

### VERIFY TOKEN
```bash
[POST]	http://localhost:8000/otp/generate
```
Body Data

```json
{
	"user_id": "<random_user_id>",
	"otp": "<otp>"
}
```
