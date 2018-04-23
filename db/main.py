import pymysql
import re

oldConn = pymysql.connect(host='localhost', user='root', password='root',
                          db='old', charset='utf8', port=8989)
newConn = pymysql.connect(host='localhost', user='root', password='root',
                          db='new', charset='utf8', port=8989)

mdnConn = pymysql.connect(host='localhost', user='root', password='root',
                          db='mdn', charset='utf8', port=8989)


def selectUser():
    conn = oldConn
    cur = conn.cursor()

    sql = "select   login_account as username, password, chinese_name,\
                    gender, identification_number, primary_e_mail as email,\
                    updatetime, spare_e_mail as alternate_email,status\
        from active_username a,pts_system_user_basic_file u where u.login_account = a.logid  \
        "
    limit = " limit 10;"
    # sql += limit
    cur.execute(sql)
    result = cur.fetchall()

    # 處理Login Name 重複的問題
    usernameCounter = dict()
    for name in result:
        name = name[0]
        if name not in usernameCounter:
            usernameCounter[name] = 1
        else:
            usernameCounter[name] += 1

    duplicateName = []
    returnValue = []
    for key, value in (sorted(usernameCounter.items(), key=lambda t: t[1], reverse=True)):
        if value == 1:
            break
        duplicateName.append(key)
        print(key, value)

    # 除外重複的Login Name
    for res in result:
        if res[0] not in duplicateName:
            returnValue.append(res)

    # print(returnValue)
    # print(returnValue)
    # [Finished in 264.3s]
    conn.close()

    return returnValue


def insertUser(data):
    conn = newConn
    cur = conn.cursor()
    try:
        for d in data:
            # print(d)
            sql = "insert into user(username, password, chinese_name,\
                                    gender, identification_number,email,\
                                    update_time,alternate_email,status  )\
                    value(%s,%s,%s ,%s,%s,%s ,%s,%s,%s);"

            cur.execute(sql, d)

    except Exception as e:
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


def selectTeacher():
    conn = mdnConn
    cur = conn.cursor()

    sql = """SELECT
                DISTINCT u.logid 
            FROM
                a_dclass c,
                a_users u 
            WHERE
                c.teacher = u.uid"""

    # sql += "limit 10"
    cur.execute(sql)
    result = cur.fetchall()
    conn.close()

    return result


def insertTeacher():
    data = selectTeacher()
    conn = newConn
    cur = conn.cursor()
    try:
        for d in data:
            sql = "call add_teacher(%s)"
            cur.execute(sql, d)

    except Exception as e:
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


def selectStudent():
    conn = oldConn
    cur = conn.cursor()

    sql = "SELECT login_account, seat \
            FROM `pts_system_school_student_file` student, pts_system_user_basic_file base\
            WHERE student.user_id = base.id"
    # sql += "limit 10"
    cur.execute(sql)
    result = cur.fetchall()
    conn.close()

    return result


def insertStudent():
    data = selectStudent()
    conn = newConn
    cur = conn.cursor()
    # print(data)
    try:
        for d in data:
            sql = "call add_student(%s, %s)"
            cur.execute(sql, d)

    except Exception as e:
        print("資料有問題 :", d)
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


def schoolHasTeacher_input():
    conn = mdnConn
    cur = conn.cursor()
    sql = """SELECT DISTINCT
                u.logid,
                s.title,
                s.`level`,
                s.address 
            FROM
                a_dclass c,
                a_dschool s,
                a_users u 
            WHERE
                c.teacher IN ( SELECT DISTINCT u.uid FROM a_dclass c, a_users u WHERE c.teacher = u.uid ) 
                AND c.sid2 = s.sid2 
                AND u.uid = c.teacher;
                """

    cur.execute(sql)
    result = cur.fetchall()
    conn.close()
    return result


def schoolHasTeacher():
    data = schoolHasTeacher_input()
    conn = newConn
    cur = conn.cursor()
    try:
        for d in data:
            sql = "call add_school_has_teacher(%s,%s,%s,%s)"
            cur.execute(sql, d)

    except Exception as e:
        print("schoolHasStudent Debug :", d)
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


def schoolHasStudent_input():
    conn = oldConn
    cur = conn.cursor()

    sql = """
        SELECT
            distinct u.login_account, s.title, s.level, s.address
        FROM
            pts_system_school_student_file f,
            all_system_school_file s,
            pts_system_user_basic_file u
        WHERE
            s.id = f.school_id
            AND u.id = f.user_id """

    # sql += "limit 10;"
    cur.execute(sql)
    result = cur.fetchall()
    conn.close()
    return result


def schoolHasStudent():
    # 請先運行insertStudent()
    data = schoolHasStudent_input()

    conn = newConn
    cur = conn.cursor()
    try:
        for d in data:
            sql = "call add_school_has_student( %s,%s,%s,%s)"
            cur.execute(sql, d)

    except Exception as e:
        print("schoolHasStudent Debug :", d)
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


def main():
    # insertStudent()
    # schoolHasStudent()
    # insertTeacher()

    # checkSchool()
    schoolHasTeacher()


def checkSchool():
    conn = newConn
    cur = conn.cursor()

    sql = "select address,type,chinese_name from school ;"
    # sql += " limit 10;"
    cur.execute(sql)
    result = cur.fetchall()

    # 處理資料重複的問題
    usernameCounter = dict()
    for name in result:
        name = name[0] + str(name[1]) + str(name[2])
        if name not in usernameCounter:
            usernameCounter[name] = 1
        else:
            usernameCounter[name] += 1

    duplicateName = []
    returnValue = []
    for key, value in (sorted(usernameCounter.items(), key=lambda t: t[1], reverse=True)):
        if value == 1:
            break
        duplicateName.append(key)
        print(key, value)

    # 除外重複的資料
    for res in result:
        if res[0] not in duplicateName:
            returnValue.append(res)

    # print(returnValue)
    # print(returnValue)
    # [Finished in 264.3s]
    conn.close()

    return returnValue


def selectSchool():
    conn = oldConn
    cur = conn.cursor()
    returnValue = []

    sql = """SELECT LEVEL AS
                type,
                title AS chinese_name,
                e_mail AS email,
                url,
                fax,
                address,
                STATUS,
                telephone AS phone
            FROM
                all_system_school_file"""

    # limit = " limit 10;"
    # sql += limit

    cur.execute(sql)
    rows = cur.fetchall()

    for r in rows:
        r = list(r)
        phone = re.sub("[^0-9]", "", r[7])

        # 長度符合台灣電話號碼10碼
        if len(phone) == 10:
            dialing_code = phone[0:2]
            phone_suf = phone[2:]
            r[7] = dialing_code
            r.append(phone_suf)
        else:
            dialing_code = ''
            r[7] = dialing_code
            r.append(phone)

        # returnValue:
        # type, chinese_name, email, url, fax, address, status,
        # dialing_code, phone

        returnValue.append(r)

    return returnValue


def insertSchoolData(data):
    conn = newConn
    cur = conn.cursor()
    try:
        for d in data:
            sql = "insert into school ( type,chinese_name, email,\
                                        url,fax,address,\
                                        status,dialing_code,phone )\
                    value(%s,%s,%s ,%s,%s,%s ,%s,%s,%s)\
                  "

            cur.execute(sql, d)

    except Exception as e:
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


def getDeptData():
    conn = oldConn
    cur = conn.cursor()
    dept = []

    sql = "select chinese_title, url, e_mail,address,telphone as phone \
            from pts_system_dept"

    cur.execute(sql)
    dept = cur.fetchall()

    # sql = "select chinese_title, url, e_mail,address,telphone as phon\
    #          from all_system_school_file s ,pts_system_dept d \
    #         where s.dept_id =d.id \
    #         "

    # cur.execute(sql)
    # dept_ext = cur.fetchall()

    # for r in rows:
    #     r = list(r)
    #     phone = re.sub("[^0-9]", "", r[4])

    #     # 長度符合台灣電話號碼10碼
    #     if len(phone) == 10:
    #         dialing_code = phone[0:2]
    #         phone_suf = phone[2:]
    #         r[4] = dialing_code
    #         r.append(phone_suf)
    #     else:
    #         dialing_code = ''
    #         r[4] = dialing_code
    #         r.append(phone)

    #     dept.append(r)

    # print(dept_ext)
    return dept, dept_ext


def uploadDeptData(dept):
    conn = newConn
    cur = conn.cursor()
    try:
        for d in dept:
            sql = "insert into dept (chinese_name, url, email,\
                                    address,dialing_code,phone)\
                    value(%s,%s,%s, %s,%s,%s)\
                  "
            cur.execute(sql, d)

    except Exception as e:
        print(d)
        raise e
        conn.rollback()

    conn.commit()
    conn.close()


# def uploadDeptExtData():
#     # sql = "select chinese_title, team_id, rent \
#     #             sms_for_gift, sms_remaining, is_enabled\
#     #             enabled_date, is_permanent_use, deadline\
#     #             updatetime \
#     #         from pts_system_dept \
#     #         "
#     try:
#         for d in dept_ext:
#             sql =
#             sql = "insert into dept_ext (chinese_title,url,email\
#                                     address ,dialing_code,phone)\
#                     value(%s,%s,%s, %s,%s,%s)\
#                   "
#             cur.execute(sql, d)

#     except Exception as e:
#         raise e
#         conn.rollback()

    # uploadUserData(Data)

    # Data = getSchoolData()
    # uploadSchoolData(Data)

    # select * from all_system_school_file s, pts_system_dept d where
    # s.dept_id = d.id
    # dept, dept_ext = getDeptData()
    # uploadDeptData(dept)
    # uploadDeptExtData(dept_ext)

    # result = cur.fetchall()
    # print(result)


if __name__ == '__main__':
    main()
