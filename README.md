## Exercice
You are going to create a new platform where users may share their idea related to projects they have in mind. Users will post projects and others will provide feedback for them via comments. For this you are required to use mysql database. 


### Required Functionalities 
- Full authentication flow, register, login, logout 
    - Allow user account confirmation via email (Optional)
- Project crud
    - Post projects 
    - Update past projects 
    - Delete projects 
    - View user projects 
    - Allow user to controll whenever: 
        - Project is visible to other users (Optional)
        - Project should allow users to comment (Optional)
- Listing of projects & Comments : *Users should see other user projects also on the index page. They should be able to post comments on these projects. Comments should be shown in the order they were posted. 
- Show a list of all coments users have made, and allow users to change
or delete them (Optional)

*Keep in mind that you are free to choose your own order related to sloving exercise parts.*

### For these features you will have to follow the database structure below. 
Schema
```yaml
user:
    id: int AUTO_INCREMENT
    name: string
    surname: stromg 
    login: unique string
    password: hashed string
    account_confirmed: boolean
    created_at: datetime    --- date when user was registered
    last_login: datetime    --- last time user was logged in

project:
    id: int AUTO_INCREMENT
    subject: string, not null 
    abstract: text, not null, (Abstract should be between 50 to 800 words)
    owner_id: user 
    created_at: datetime 
    visible: boolean 
    allow_comments: boolean 


project_comments:
    id: int AUTO_INCREMENT
    project_id: project     ---- project that is commented  
    user_id: user          ----- user who is commenting 
    comment: text 
    created_at: datetime    ---- when was comment created 
```