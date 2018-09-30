# nette-sandbox

## Main repository for 98th PoSobota in Prague

- Added .gitignore files to project root, /temp and /log dirs 
- Strict types! 
- Directory structure changed to corespond with PHP namespaces (PSR-0, PSR-4)
- Use full class names only in use-statements, not in class body
- `nette/database` replaced in favor of `dibi`
- SRP: use class App\Authentication\Credentials, not some array
- SRP: Authenticator to separate class (App\Authentication\UserAuthenticator)
- SRP: UserAuthenticator is not touching the database, but UserDataProvider + UserData
- SRP: created IdentityFactory, UserAuthenticator returning UserData
- SRP: script bin/create-user.php is now using UserDataStorage
- Tests: IdentityFactoryTest, UserAuthenticatorTest
- composer.json: removed unnecessary packages
- Fixed code sniffer & phpstan errors
- Added .travis-ci.yml to run tests & code sniffer & phpstan
