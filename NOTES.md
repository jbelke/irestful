
possible instructions:

"retrieve": {
    "pattern": "/\\/[a-z]+/s",
    "input": "input",
    "constants": {
        "role_title": "This is a role"
    },
    "http_requests": {
        "retrieve_from_github": {
            "command": "retrieve http://github.com/myuri:80",
            "parameters": {
                "query": {
                    "api_key": "[github_api_key]",
                    "name": "input->name",
                    "another": "my_value"
                }
            },
            "view": "json"
        },
        "insert_user_in_github": {
            "command": "insert http://github.com/myuri:80",
            "parameters": {
                "query": {
                    "another": "my_value"
                },
                "request": {
                    "name": "input->name"
                }
            },
            "headers": {
                "api_key": "[github_api_key]"
            },
            "view": "json"
        },
        "delete_user_in_github": {
            "command": "delete http://github.com/myuri:80",
            "parameters": {
                "query": {
                    "another": "my_value"
                },
                "request": {
                    "name": "input->name"
                }
            },
            "headers": {
                "api_key": "[github_api_key]"
            },
            "view": "json"
        }
    },
    "instructions": [
        "githubData = execute retrieve_from_github",
        "github = from githubData to endpoint",
        "role = retrieve role by uuid:input->uuid",
        "roleData = from role to data",
        "roleAgain = from roleData to role",
        "secondGithubData = from github to data",
        "merged = merge roleData, secondGithubData",

        "execute insert_user_in_github",
        "execute delete_user_in_github",

        "roles = retrieve role index input->min amount input->max",
        "anotherRole = retrieve role by title:constants->role_title",
        "multipleRoles = retrieve multiple role by uuid:input->uuids",
        "anotherMultipleRoles = retrieve multiple role by input->keyname:input->value",

        "roleToo = from input to role",
        "insert roleToo",
        "delete roleToo",

        "rolesAgain = from input to multiple role",
        "insert rolesAgain",
        "delete rolesAgain, roleToo",
        "insert rolesAgain, roleToo",

        "newRole = from input to role",
        "oldRole = retrieve role by uuid:input->uuid",
        "update oldRole using newRole",
        "output = from newRole to data",
        "return merge secondGithubData, output",
        "return output"
    ],
    "view": "json"
}