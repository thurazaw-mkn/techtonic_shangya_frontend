INSERT INTO admins (
    username,
    display_name,
    password,
    role,
    email,
    company_id,
    status,
    is_deleted,
    created_at,
    created_by,
    updated_at,
    updated_by
) VALUES (
    'testcompany',
    'Test Company',
    SHA2('User@123', 256), -- You can use your own hashing or plain text depending on your auth system
    'employee',
    'superadmin@example.com',
    0, 
    1,    -- Active status
    FALSE,
    NOW(),
    'system',
    NOW(),
    'system'
);
