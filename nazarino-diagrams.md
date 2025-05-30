    # نمودارهای پروژه نظرینو با Mermaid.js

    ## نمودار زمینه (Context Diagram)

    ```mermaid
    graph TD
        A[سیستم نظرینو] --- B[کاربران عادی]
        A --- C[کاربران ویژه]
        A --- D[مدیران سیستم]
        A --- E[سیستم پرداخت]
        A --- F[سیستم تحلیل هوش مصنوعی]

        style A fill:#f9f,stroke:#333,stroke-width:2px
        style B fill:#bbf,stroke:#333,stroke-width:1px
        style C fill:#bbf,stroke:#333,stroke-width:1px
        style D fill:#bbf,stroke:#333,stroke-width:1px
        style E fill:#bfb,stroke:#333,stroke-width:1px
        style F fill:#bfb,stroke:#333,stroke-width:1px
    ```

    ## نمودار جریان داده (DFD) - سطح 0

    ```mermaid
    graph TD
        U[کاربران] -->|درخواست| A
        A[1. مدیریت کاربران] -->|پاسخ| U
        U -->|درخواست| B
        B[2. مدیریت نظرسنجی‌ها] -->|پاسخ| U
        U -->|درخواست| C
        C[3. مدیریت اشتراک‌ها] -->|پاسخ| U
        U -->|درخواست| D
        D[4. تحلیل و گزارش‌گیری] -->|پاسخ| U
        U -->|درخواست| E
        E[5. مدیریت نقش‌ها و دسترسی‌ها] -->|پاسخ| U
        
        A <-->|خواندن/نوشتن| DB1[(پایگاه داده)]
        B <-->|خواندن/نوشتن| DB1
        C <-->|خواندن/نوشتن| DB1
        D <-->|خواندن| DB1
        E <-->|خواندن/نوشتن| DB1
        
        style A fill:#f96,stroke:#333,stroke-width:2px
        style B fill:#f96,stroke:#333,stroke-width:2px
        style C fill:#f96,stroke:#333,stroke-width:2px
        style D fill:#f96,stroke:#333,stroke-width:2px
        style E fill:#f96,stroke:#333,stroke-width:2px
        style U fill:#bbf,stroke:#333,stroke-width:1px
        style DB1 fill:#bfb,stroke:#333,stroke-width:1px
    ```

    ## نمودار جریان داده (DFD) - سطح 1 (مدیریت نظرسنجی‌ها)

    ```mermaid
    graph TD
        U[کاربران] -->|درخواست ایجاد| A
        A[2.1 ایجاد نظرسنجی] -->|پاسخ| U
        U -->|درخواست| B
        B[2.2 مدیریت گزینه‌ها] -->|پاسخ| U
        U -->|رأی| C
        C[2.3 ثبت رأی] -->|تأیید| U
        U -->|نظر| D
        D[2.4 مدیریت نظرات] -->|پاسخ| U
        U -->|درخواست صادرات| E
        E[2.5 صادرات داده‌ها] -->|فایل Excel| U
        
        A -->|ذخیره| DB1[(نظرسنجی‌ها)]
        B <-->|خواندن/نوشتن| DB2[(گزینه‌ها)]
        C -->|ذخیره| DB3[(آرا)]
        D <-->|خواندن/نوشتن| DB4[(نظرات)]
        E <--|خواندن| DB1
        E <--|خواندن| DB2
        E <--|خواندن| DB3
        E <--|خواندن| DB4
        
        style A fill:#f96,stroke:#333,stroke-width:2px
        style B fill:#f96,stroke:#333,stroke-width:2px
        style C fill:#f96,stroke:#333,stroke-width:2px
        style D fill:#f96,stroke:#333,stroke-width:2px
        style E fill:#f96,stroke:#333,stroke-width:2px
        style U fill:#bbf,stroke:#333,stroke-width:1px
        style DB1 fill:#bfb,stroke:#333,stroke-width:1px
        style DB2 fill:#bfb,stroke:#333,stroke-width:1px
        style DB3 fill:#bfb,stroke:#333,stroke-width:1px
        style DB4 fill:#bfb,stroke:#333,stroke-width:1px
    ```

    ## نمودار ارتباط موجودیت‌ها (ERD)

    ```mermaid
    erDiagram
        USER {
            int id PK
            string username
            string first_name
            string last_name
            string email
            datetime email_verified_at
            string password
            string phone_number
            datetime created_at
            datetime updated_at
        }
        
        ROLE {
            int id PK
            string name
            string display_name
            string description
            datetime created_at
            datetime updated_at
        }
        
        PERMISSION {
            int id PK
            string name
            string display_name
            string description
            datetime created_at
            datetime updated_at
        }
        
        ELECTION {
            int id PK
            int user_id FK
            string title
            string description
            boolean is_public
            boolean is_open
            datetime end_date
            datetime created_at
            datetime updated_at
        }
        
        OPTION {
            int id PK
            int election_id FK
            string title
            string description
            datetime created_at
            datetime updated_at
        }
        
        VOTE {
            int id PK
            int user_id FK
            int option_id FK
            int vote
            datetime created_at
            datetime updated_at
        }
        
        COMMENT {
            int id PK
            int user_id FK
            int election_id FK
            string content
            datetime created_at
            datetime updated_at
        }
        
        SUBSCRIPTION_TIER {
            int id PK
            string title
            string description
            decimal price
            datetime created_at
            datetime updated_at
        }
        
        SUB_FEATURE {
            int id PK
            string name
            string key
            string description
            datetime created_at
            datetime updated_at
        }
        
        SUBSCRIPTION_USER {
            int id PK
            int user_id FK
            int subscription_tier_id FK
            string status
            datetime starts_at
            datetime ends_at
            string transaction_id
            datetime created_at
            datetime updated_at
        }
        
        FOLLOWER {
            int id PK
            int follower_id FK
            int following_id FK
            datetime created_at
            datetime updated_at
        }
        
        AI_ANALYSIS {
            int id PK
            int election_id FK
            json analysis_data
            datetime created_at
            datetime updated_at
        }
        
        USER ||--o{ ELECTION : "creates"
        USER ||--o{ VOTE : "casts"
        USER ||--o{ COMMENT : "writes"
        USER ||--o{ SUBSCRIPTION_USER : "subscribes"
        USER }|--|| FOLLOWER : "follows"
        USER }o--o{ ROLE : "has"
        ROLE }o--o{ PERMISSION : "has"
        ELECTION ||--o{ OPTION : "contains"
        ELECTION ||--o{ COMMENT : "has"
        ELECTION ||--o| AI_ANALYSIS : "has"
        OPTION ||--o{ VOTE : "receives"
        SUBSCRIPTION_TIER ||--o{ SUBSCRIPTION_USER : "has"
        SUBSCRIPTION_TIER }o--o{ SUB_FEATURE : "includes"
    ```

    ## نمودار کلاس (Class Diagram)

    ```mermaid
    classDiagram
        class User {
            +id: int
            +username: string
            +first_name: string
            +last_name: string
            +email: string
            +password: string
            +phone_number: string
            +roles(): BelongsToMany
            +elections(): HasMany
            +subscriptions(): HasMany
            +hasRole(role): bool
            +hasActiveSubscriptionTier(): bool
        }
        
        class Role {
            +id: int
            +name: string
            +display_name: string
            +description: string
            +permissions(): BelongsToMany
            +users(): BelongsToMany
        }
        
        class Permission {
            +id: int
            +name: string
            +display_name: string
            +description: string
            +roles(): BelongsToMany
        }
        
        class Election {
            +id: int
            +user_id: int
            +title: string
            +description: string
            +is_public: bool
            +is_open: bool
            +end_date: datetime
            +user(): BelongsTo
            +options(): HasMany
            +comments(): HasMany
            +getTotalVotes(): int
            +getDetailedVoteStats(): array
            +getTotalComments(): int
        }
        
        class Option {
            +id: int
            +election_id: int
            +title: string
            +description: string
            +election(): BelongsTo
            +votes(): HasMany
        }
        
        class Vote {
            +id: int
            +user_id: int
            +option_id: int
            +vote: int
            +user(): BelongsTo
            +option(): BelongsTo
        }
        
        class SubscriptionTier {
            +id: int
            +title: string
            +description: string
            +price: decimal
            +subFeatures(): BelongsToMany
            +subscriptionUsers(): HasMany
        }
        
        class SubFeature {
            +id: int
            +name: string
            +key: string
            +description: string
            +subscriptionTiers(): BelongsToMany
        }
        
        class SubscriptionUser {
            +id: int
            +user_id: int
            +subscription_tier_id: int
            +status: string
            +starts_at: datetime
            +ends_at: datetime
            +transaction_id: string
            +user(): BelongsTo
            +subscriptionTier(): BelongsTo
        }
        
        User "1" --> "*" Election: creates
        User "1" --> "*" Vote: casts
        User "*" --> "*" Role: has
        Role "*" --> "*" Permission: has
        Election "1" --> "*" Option: contains
        Option "1" --> "*" Vote: receives
        SubscriptionTier "1" --> "*" SubscriptionUser: has
        SubscriptionTier "*" --> "*" SubFeature: includes
        User "1" --> "*" SubscriptionUser: subscribes
    ```

    ## نمودار توالی (Sequence Diagram) - فرآیند ایجاد نظرسنجی

    ```mermaid
    sequenceDiagram
        actor User
        participant Controller as ElectionController
        participant Model as Election
        participant DB as Database
        
        User->>Controller: درخواست ایجاد نظرسنجی
        Controller->>Controller: اعتبارسنجی داده‌ها
        Controller->>Model: ایجاد نظرسنجی جدید
        Model->>DB: ذخیره در پایگاه داده
        DB-->>Model: تأیید ذخیره‌سازی
        Model-->>Controller: بازگشت نظرسنجی ایجاد شده
        Controller-->>User: نمایش پیام موفقیت
        
        User->>Controller: درخواست افزودن گزینه‌ها
        Controller->>Controller: اعتبارسنجی داده‌ها
        Controller->>Model: ایجاد گزینه‌های جدید
        Model->>DB: ذخیره در پایگاه داده
        DB-->>Model: تأیید ذخیره‌سازی
        Model-->>Controller: بازگشت گزینه‌های ایجاد شده
        Controller-->>User: نمایش پیام موفقیت
    ```

    ## نمودار توالی (Sequence Diagram) - فرآیند صادرات Excel

    ```mermaid
    sequenceDiagram
        actor User
        participant Controller as ElectionController
        participant Model as Election
        participant Export as AdminElectionExport
        participant Excel as Laravel Excel
        
        User->>Controller: درخواست صادرات Excel
        Controller->>Model: دریافت داده‌های نظرسنجی
        Model-->>Controller: بازگشت داده‌ها
        Controller->>Export: ایجاد کلاس صادرات با داده‌ها
        Export->>Export: تنظیم عناوین و قالب‌بندی
        Controller->>Excel: درخواست دانلود
        Excel->>Excel: ایجاد فایل Excel
        Excel-->>User: دانلود فایل Excel
    ```

    ## نمودار فعالیت (Activity Diagram) - فرآیند مدیریت اشتراک

    ```mermaid
    graph TD
        A[شروع] --> B{کاربر اشتراک دارد؟}
        B -->|بله| C[بررسی وضعیت اشتراک]
        B -->|خیر| D[نمایش گزینه‌های اشتراک]
        C -->|فعال| E[نمایش اطلاعات اشتراک]
        C -->|منقضی شده| D
        D --> F[انتخاب سطح اشتراک]
        F --> G[پرداخت هزینه اشتراک]
        G -->|موفق| H[ایجاد رکورد اشتراک جدید]
        G -->|ناموفق| I[نمایش خطای پرداخت]
        H --> J[فعال‌سازی قابلیت‌های اشتراک]
        I --> D
        J --> K[پایان]
        E --> K
        
        style A fill:#f9f,stroke:#333,stroke-width:2px
        style B fill:#bbf,stroke:#333,stroke-width:2px
        style C fill:#bbf,stroke:#333,stroke-width:2px
        style D fill:#bbf,stroke:#333,stroke-width:2px
        style E fill:#bbf,stroke:#333,stroke-width:2px
        style F fill:#bbf,stroke:#333,stroke-width:2px
        style G fill:#bbf,stroke:#333,stroke-width:2px
        style H fill:#bbf,stroke:#333,stroke-width:2px
        style I fill:#bbf,stroke:#333,stroke-width:2px
        style J fill:#bbf,stroke:#333,stroke-width:2px
        style K fill:#f9f,stroke:#333,stroke-width:2px
    ```

    ## نمودار استقرار (Deployment Diagram)

    ```mermaid
    graph TD
        A[مرورگر کاربر] -->|HTTP/HTTPS| B[وب سرور]
        B -->|PHP-FPM| C[اپلیکیشن Laravel]
        C -->|SQL| D[(پایگاه داده MySQL)]
        C -->|Redis| E[(Redis Cache)]
        C -->|API| F[سرویس پرداخت]
        C -->|API| G[سرویس تحلیل هوش مصنوعی]
        
        style A fill:#bbf,stroke:#333,stroke-width:1px
        style B fill:#bfb,stroke:#333,stroke-width:1px
        style C fill:#f96,stroke:#333,stroke-width:2px
        style D fill:#bfb,stroke:#333,stroke-width:1px
        style E fill:#bfb,stroke:#333,stroke-width:1px
        style F fill:#bbf,stroke:#333,stroke-width:1px
        style G fill:#bbf,stroke:#333,stroke-width:1px
    ```

    ## نکات استفاده از Mermaid.js

    برای استفاده از این نمودارها در مستندات خود:

    1. این کدها را در فایل Markdown قرار دهید
    2. اطمینان حاصل کنید که پلتفرم یا ویرایشگر شما از Mermaid.js پشتیبانی می‌کند
    3. برای مشاهده آنلاین می‌توانید از [Mermaid Live Editor](https://mermaid.live/) استفاده کنید
    4. برای سفارشی‌سازی بیشتر، به [مستندات رسمی Mermaid.js](https://mermaid.js.org/intro/) مراجعه کنید

    ### انواع دیگر نمودارهای قابل پشتیبانی در Mermaid.js

    - نمودار گانت (Gantt Chart)
    - نمودار پای (Pie Chart)
    - نمودار حالت (State Diagram)
    - نمودار جریان کار (Flowchart)
    - و موارد دیگر
