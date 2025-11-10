<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Specialization;
use App\Models\User;

class CardiologyQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على تخصص طب القلب
        $cardiology = Specialization::where('name_ar', 'طب القلب')->first();
        
        if (!$cardiology) {
            $this->command->error('تخصص طب القلب غير موجود في قاعدة البيانات!');
            return;
        }

        // الحصول على أول مستخدم (أو إنشاء مستخدم افتراضي)
        $admin = User::role(['admin', 'super-admin'])->first() 
                 ?? User::first();
        
        if (!$admin) {
            $this->command->error('لا يوجد مستخدم في النظام! يرجى إنشاء مستخدم أولاً.');
            return;
        }

        $questions = [
            [
                'question_text' => 'ما هي العوامل الرئيسية التي تزيد من خطر الإصابة بمرض الشريان التاجي؟',
                'difficulty_level' => 'medium',
                'explanation' => 'تشمل العوامل الرئيسية: التقدم في العمر، الجنس (الرجال أكثر عرضة)، التاريخ العائلي، ارتفاع ضغط الدم، ارتفاع الكوليسترول، السكري، السمنة، قلة النشاط البدني، التدخين، والتوتر المستمر.',
                'options' => [
                    ['text' => 'التدخين وارتفاع ضغط الدم فقط', 'is_correct' => false],
                    ['text' => 'التدخين، ارتفاع ضغط الدم، ارتفاع الكوليسترول، السكري، السمنة، قلة النشاط البدني، والتاريخ العائلي', 'is_correct' => true],
                    ['text' => 'التقدم في العمر فقط', 'is_correct' => false],
                    ['text' => 'التوتر النفسي فقط', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هي الأعراض الشائعة لفشل القلب؟',
                'difficulty_level' => 'easy',
                'explanation' => 'تتضمن الأعراض: ضيق التنفس، التعب الشديد، تورم في الساقين والكاحلين، وزيادة سريعة في الوزن بسبب احتباس السوائل.',
                'options' => [
                    ['text' => 'ألم في الصدر فقط', 'is_correct' => false],
                    ['text' => 'ضيق التنفس، التعب الشديد، تورم في الساقين والكاحلين، وزيادة سريعة في الوزن', 'is_correct' => true],
                    ['text' => 'الصداع والدوار فقط', 'is_correct' => false],
                    ['text' => 'ارتفاع درجة الحرارة', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هي الخيارات العلاجية المتاحة لأمراض صمامات القلب؟',
                'difficulty_level' => 'medium',
                'explanation' => 'تشمل العلاجات: الأدوية، ترميم أو استبدال الصمام جراحيًا، أو عبر إجراءات طفيفة التوغل.',
                'options' => [
                    ['text' => 'الأدوية فقط', 'is_correct' => false],
                    ['text' => 'الجراحة فقط', 'is_correct' => false],
                    ['text' => 'الأدوية، ترميم أو استبدال الصمام جراحيًا، أو عبر إجراءات طفيفة التوغل', 'is_correct' => true],
                    ['text' => 'العلاج الطبيعي فقط', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'كيف يمكن الوقاية من أمراض القلب؟',
                'difficulty_level' => 'easy',
                'explanation' => 'يمكن الوقاية من خلال: الامتناع عن التدخين، اتباع نظام غذائي صحي، ممارسة التمارين الرياضية بانتظام، التحكم في ضغط الدم والكوليسترول، والحفاظ على وزن صحي.',
                'options' => [
                    ['text' => 'الامتناع عن التدخين، اتباع نظام غذائي صحي، ممارسة التمارين الرياضية بانتظام، التحكم في ضغط الدم والكوليسترول', 'is_correct' => true],
                    ['text' => 'تناول الأدوية فقط', 'is_correct' => false],
                    ['text' => 'الراحة التامة', 'is_correct' => false],
                    ['text' => 'تجنب ممارسة الرياضة', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو دور جهاز مقوّم نظم القلب ومزيل الرجفان القابل للزرع (ICD) في علاج فشل القلب؟',
                'difficulty_level' => 'hard',
                'explanation' => 'يُستخدم الجهاز لمراقبة نظم القلب وتصحيح النبضات غير الطبيعية، ويمكنه إرسال صدمات كهربائية لإعادة القلب إلى نظمه الطبيعي عند الضرورة.',
                'options' => [
                    ['text' => 'تحسين تدفق الدم فقط', 'is_correct' => false],
                    ['text' => 'مراقبة نظم القلب وتصحيح النبضات غير الطبيعية وإرسال صدمات كهربائية عند الضرورة', 'is_correct' => true],
                    ['text' => 'تقليل ضغط الدم', 'is_correct' => false],
                    ['text' => 'تحسين وظيفة الصمامات', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هي الأعراض الشائعة لأمراض القلب؟',
                'difficulty_level' => 'easy',
                'explanation' => 'تتضمن الأعراض: ألم أو ضيق في الصدر، ضيق التنفس، خفقان القلب، الدوار، والتعب الشديد.',
                'options' => [
                    ['text' => 'ألم أو ضيق في الصدر، ضيق التنفس، خفقان القلب، الدوار، والتعب الشديد', 'is_correct' => true],
                    ['text' => 'ارتفاع درجة الحرارة والسعال فقط', 'is_correct' => false],
                    ['text' => 'ألم في البطن فقط', 'is_correct' => false],
                    ['text' => 'طفح جلدي', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'كيف يتم تشخيص أمراض القلب؟',
                'difficulty_level' => 'medium',
                'explanation' => 'يتم التشخيص من خلال التاريخ الطبي، الفحص البدني، واختبارات مثل تخطيط كهربية القلب (ECG)، تخطيط صدى القلب، واختبارات الإجهاد.',
                'options' => [
                    ['text' => 'الفحص البدني فقط', 'is_correct' => false],
                    ['text' => 'التاريخ الطبي، الفحص البدني، تخطيط كهربية القلب (ECG)، تخطيط صدى القلب، واختبارات الإجهاد', 'is_correct' => true],
                    ['text' => 'فحص الدم فقط', 'is_correct' => false],
                    ['text' => 'الأشعة السينية فقط', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو فشل القلب وما أسبابه؟',
                'difficulty_level' => 'medium',
                'explanation' => 'فشل القلب هو حالة يعجز فيها القلب عن ضخ الدم بكفاءة. من أسبابه: أمراض الشرايين التاجية، ارتفاع ضغط الدم، وأمراض صمامات القلب.',
                'options' => [
                    ['text' => 'حالة يعجز فيها القلب عن ضخ الدم بكفاءة. من أسبابه: أمراض الشرايين التاجية، ارتفاع ضغط الدم، وأمراض صمامات القلب', 'is_correct' => true],
                    ['text' => 'توقف القلب تماماً', 'is_correct' => false],
                    ['text' => 'سرعة نبضات القلب فقط', 'is_correct' => false],
                    ['text' => 'انخفاض ضغط الدم فقط', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هي أمراض صمامات القلب وكيف يتم علاجها؟',
                'difficulty_level' => 'hard',
                'explanation' => 'تحدث أمراض صمامات القلب عندما لا تعمل صمامات القلب بشكل صحيح. قد يتطلب العلاج الأدوية أو الجراحة لترميم أو استبدال الصمام المتضرر.',
                'options' => [
                    ['text' => 'حالات لا تعمل فيها صمامات القلب بشكل صحيح. العلاج: الأدوية أو الجراحة لترميم أو استبدال الصمام', 'is_correct' => true],
                    ['text' => 'التهاب في عضلة القلب فقط', 'is_correct' => false],
                    ['text' => 'انسداد في الشرايين فقط', 'is_correct' => false],
                    ['text' => 'عدم انتظام نبضات القلب فقط', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو الداء القلبي الخلقي وكيف يتم التعامل معه؟',
                'difficulty_level' => 'hard',
                'explanation' => 'هو عيوب خلقية في بنية القلب موجودة منذ الولادة. يعتمد العلاج على نوع العيب وقد يشمل المراقبة، الأدوية، أو الجراحة.',
                'options' => [
                    ['text' => 'عيوب خلقية في بنية القلب موجودة منذ الولادة. العلاج: المراقبة، الأدوية، أو الجراحة حسب نوع العيب', 'is_correct' => true],
                    ['text' => 'أمراض القلب المكتسبة في الكبر', 'is_correct' => false],
                    ['text' => 'التهاب القلب الحاد', 'is_correct' => false],
                    ['text' => 'فشل القلب المزمن', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو تخطيط كهربية القلب (ECG) وما فائدته؟',
                'difficulty_level' => 'medium',
                'explanation' => 'تخطيط كهربية القلب هو اختبار يسجل النشاط الكهربائي للقلب. يساعد في تشخيص عدم انتظام ضربات القلب، النوبات القلبية، ومشاكل أخرى في القلب.',
                'options' => [
                    ['text' => 'اختبار يسجل النشاط الكهربائي للقلب ويساعد في تشخيص عدم انتظام ضربات القلب والنوبات القلبية', 'is_correct' => true],
                    ['text' => 'اختبار لقياس ضغط الدم', 'is_correct' => false],
                    ['text' => 'اختبار لفحص وظيفة الرئتين', 'is_correct' => false],
                    ['text' => 'اختبار لفحص الدم', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هي النوبة القلبية وما أعراضها؟',
                'difficulty_level' => 'medium',
                'explanation' => 'النوبة القلبية تحدث عندما يتم حظر تدفق الدم إلى القلب. الأعراض تشمل: ألم في الصدر، ضيق التنفس، التعرق، الغثيان، والدوار.',
                'options' => [
                    ['text' => 'حالة يتم فيها حظر تدفق الدم إلى القلب. الأعراض: ألم في الصدر، ضيق التنفس، التعرق، الغثيان، والدوار', 'is_correct' => true],
                    ['text' => 'سرعة نبضات القلب فقط', 'is_correct' => false],
                    ['text' => 'انخفاض ضغط الدم فقط', 'is_correct' => false],
                    ['text' => 'ألم في البطن', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو دور الأسبرين في الوقاية من أمراض القلب؟',
                'difficulty_level' => 'hard',
                'explanation' => 'الأسبرين يعمل كمضاد للصفيحات الدموية، مما يساعد على منع تكوين الجلطات الدموية. يُستخدم في الوقاية من النوبات القلبية والسكتات الدماغية عند المرضى المعرضين للخطر.',
                'options' => [
                    ['text' => 'يعمل كمضاد للصفيحات الدموية ويساعد على منع تكوين الجلطات الدموية', 'is_correct' => true],
                    ['text' => 'يخفض ضغط الدم', 'is_correct' => false],
                    ['text' => 'يقلل الكوليسترول', 'is_correct' => false],
                    ['text' => 'يحسن وظيفة الصمامات', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو ارتفاع ضغط الدم وكيف يؤثر على القلب؟',
                'difficulty_level' => 'easy',
                'explanation' => 'ارتفاع ضغط الدم هو حالة يكون فيها ضغط الدم مرتفعاً باستمرار. هذا يجعل القلب يعمل بجهد أكبر لضخ الدم، مما قد يؤدي إلى تضخم القلب وفشل القلب.',
                'options' => [
                    ['text' => 'حالة يكون فيها ضغط الدم مرتفعاً باستمرار، مما يجعل القلب يعمل بجهد أكبر وقد يؤدي إلى تضخم القلب وفشل القلب', 'is_correct' => true],
                    ['text' => 'انخفاض ضغط الدم', 'is_correct' => false],
                    ['text' => 'عدم انتظام نبضات القلب', 'is_correct' => false],
                    ['text' => 'التهاب في القلب', 'is_correct' => false],
                ]
            ],
            [
                'question_text' => 'ما هو الكوليسترول وما تأثيره على صحة القلب؟',
                'difficulty_level' => 'medium',
                'explanation' => 'الكوليسترول هو مادة دهنية في الدم. ارتفاع الكوليسترول الضار (LDL) يمكن أن يؤدي إلى تراكم الترسبات في الشرايين، مما يزيد من خطر الإصابة بأمراض القلب والنوبات القلبية.',
                'options' => [
                    ['text' => 'مادة دهنية في الدم. ارتفاع الكوليسترول الضار يمكن أن يؤدي إلى تراكم الترسبات في الشرايين ويزيد من خطر أمراض القلب', 'is_correct' => true],
                    ['text' => 'بروتين في الدم', 'is_correct' => false],
                    ['text' => 'سكر في الدم', 'is_correct' => false],
                    ['text' => 'هرمون', 'is_correct' => false],
                ]
            ],
        ];

        $this->command->info('بدء إضافة أسئلة طب القلب...');

        foreach ($questions as $index => $questionData) {
            $question = Question::create([
                'question_text' => $questionData['question_text'],
                'specialization_id' => $cardiology->id,
                'difficulty_level' => $questionData['difficulty_level'],
                'explanation' => $questionData['explanation'],
                'time_limit' => 60,
                'points' => 1,
                'is_active' => true,
                'is_approved' => true,
                'created_by' => $admin->id,
                'tags' => ['طب القلب', 'cardiology'],
            ]);

            foreach ($questionData['options'] as $order => $optionData) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct'],
                    'sort_order' => $order + 1,
                ]);
            }

            $this->command->info("تم إضافة السؤال " . ($index + 1) . ": " . substr($questionData['question_text'], 0, 50) . "...");
        }

        $this->command->info('تم إضافة ' . count($questions) . ' سؤال في طب القلب بنجاح!');
    }
}
