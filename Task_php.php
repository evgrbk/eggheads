        <?
        //1. Корректен ли данный участок кода
        //...
        $mysqli = new mysqli("localhost", "my_user", "my_password", "world");
        $id = $_GET['id'];
        $res = $mysqli->query('SELECT * FROM users WHERE u_id=' . $id);
        $user = $res->fetch_assoc();
        //…
        //Если да, то применил ли его в своем коде?


        //Ответ:

        //Код не безопасен в плане sql-инъекций. Решением может быть prepare, плэйсхолдеры mysql_real_escape_string($_GET['userid'])





        //2. Оптимизируй
        //...
        $questionsQ = $mysqli->query('SELECT * FROM questions WHERE catalog_id=' . $catId);
        $result = array();
        while ($question = $questionsQ->fetch_assoc()) {
            $userQ = $mysqli->query('SELECT name, gender FROM users WHERE id=' . $question['user_id']);
            $user = $userQ->fetch_assoc();
            $result[] = array('question' => $question, 'user' => $user);
            $userQ->free();
        }
        $questionsQ->free();
        //...


        //////////////////////////////////////////
        /// Ответ:
        ///
        function stmt_bind_assoc(&$stmt, &$out)
        {
            $data = mysqli_stmt_result_metadata($stmt);
            $fields = array();
            $out = array();

            $fields[0] = $stmt;
            $count = 1;

            while ($field = mysqli_fetch_field($data)) {
                $fields[$count] = &$out[$field->name];
                $count++;
            }
            call_user_func_array(mysqli_stmt_bind_result, $fields);
        }


        $stmt = $mysqli->prepare("SELECT q.*,u.name,u.gender FROM questions q JOIN  users u ON  q.user_id = u.id  where catalog_id=?");
        $stmt->bind_param('i', intval($catId));
        $stmt->execute();
        $stmt->store_result();

        $resultrow = array();
        stmt_bind_assoc($stmt, $resultrow);
        while ($stmt->fetch()) {
            print_r($resultrow);
        }

        $stmt->free_result();
        $stmt->close();
        $mysqli->close();





        //3. Напиши SQL-запрос
        //Имеем следующие таблицы:
        //1. users — контрагенты
        //1. id
        //2. name
        //3. phone
        //4. email
        //5. created — дата создания записи
        //2. orders — заказы
        //1. id
        //2. subtotal — сумма всех товарных позиций
        //3. created — дата и время поступления заказа (Y-m-d H:i:s)
        //4. city_id — город доставки
        //5. user_id

        //Необходимо выбрать одним запросом следующее (следует учесть, что могут быть контрагенты, не сделавшие ни одного заказа):
        //1. Имя контрагента
        //2. Его телефон
        //3. Сумма всех его заказов
        //4. Его средний чек
        //5. Дата последнего заказа

        SELECT
           u.name,u.phone,
           IFNULL(SUM(o.subtotal), 0),IFNULL(avg(o.subtotal), 0),IFNULL(max(o.created), 'Еще не заказывал')
        FROM orders o  right join users u on  o.user_id = u.id
        GROUP by u.id