<?php

namespace Model;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class User
{
    use Model;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $loginUniqueColumn = 'email';

    protected $allowedColumns = [
        'fullname',
        'phone',
        'email',
        'password',
    ];

    protected $onInsertValidationRules = [
        'fullname' => [
            'alpha_space',
            'required',
        ],
        'password' => [
            'not_less_than_8_chars',
            'required',
        ],
        'email' => [
            'unique',
            'email',
            'required',
        ],
    ];

    protected $onUpdateValidationRules = [
        'fullname' => [
            'alpha_space',
            'required',
        ],
        'password' => [
            'not_less_than_8_chars',
            'required',
        ],
        'email' => [
            'unique',
            'email',
            'required',
        ],
    ];

    /**
     * Function to handle user signup process.
     *
     * @param array $data The user data to be inserted.
     * @return void
     *
     * @since 1.0.0
     */
    public function signup($data)
    {
        // Validate the user data
        if ($this->validate($data))
        {
            if ($data['password'] == $data['password2'])
            {
                // Hash the password using bcrypt
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

                // Set the current date and time for 'date' field
                $data['date'] = date("Y-m-d H:i:s");

                // Add other needed user columns here
                $data['usertype'] = 'user';

                // Insert the user data into the database
                $this->insert($data);

                // Redirect the user to the login page
                redirect('signin');
            }
            else
            {
                $this->errors['password'] = "Passwords do not match";
                $this->errors['password2'] = "Passwords do not match";
            }
        }
    }

    /**
     * Function to handle user signin process.
     *
     * @param array $data The user data to be validated and authenticated.
     * @return void
     *
     *
     * @since 1.0.0
     */
    public function signin($data)
    {
        // Fetch the user record from the database using the email
        $row = $this->first([$this->loginUniqueColumn=>$data['email']]);

        // Check if a user record was found
        if ($row)
        {
            // Verify the password using bcrypt
            if (password_verify($data['password'], $row->password))
            {
                // Initialize the session class
                $sesh = new \Core\Session;

                // Authenticate the user by storing user data in the session
                $sesh->auth($row);

                // Redirect the user to the admin or home page
                $array = $sesh->user();

                if ($array->usertype == 'admin')
                {
                    redirect('dash');
                }
                else
                {
                    redirect('home');
                }  
            }
            else
            {
                // Set an error message for invalid credentials
                $this->errors[$this->loginUniqueColumn] = "Invalid Credentials";
            }
        }
        else
        {
            // Set an error message for invalid credentials
            $this->errors[$this->loginUniqueColumn] = "Invalid Credentials";
        }
    }
}